#!/bin/bash
# Script de instalación y configuración del Servidor

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Variables de configuración
DOMAIN="servicios.local"
DB_NAME="servicios_db"
DB_USER="servicios_user"
DB_PASS=$(openssl rand -base64 12)
ADMIN_USER="admin_servicios"
ADMIN_PASS=$(openssl rand -base64 12)
WEB_ROOT="/var/www/html/servicios"
BACKUP_DIR="/opt/servicios/backups"
LOG_DIR="/var/log/servicios"

# Funciones de utilidad
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1" | tee -a $LOG_DIR/install.log
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a $LOG_DIR/install.log
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a $LOG_DIR/install.log
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a $LOG_DIR/install.log
}

verificar_root() {
    if [ "$EUID" -ne 0 ]; then
        log_error "Este script debe ejecutarse como root"
        exit 1
    fi
}

# Función para crear estructura de directorios
crear_directorios() {
    log_info "Creando estructura de directorios."
    
    # Directorios principales
    mkdir -p $WEB_ROOT
    mkdir -p $BACKUP_DIR
    mkdir -p $LOG_DIR
    mkdir -p /opt/servicios/config
    mkdir -p /opt/servicios/scripts
    mkdir -p /opt/servicios/uploads
    
    # Directorios de la aplicación
    mkdir -p $WEB_ROOT/{app,public,config,uploads,logs}
    mkdir -p $WEB_ROOT/app/{controllers,models,views,core}
    mkdir -p $WEB_ROOT/public/{css,js,images,uploads}
    
    # Permisos
    chown -R apache:apache $WEB_ROOT
    chmod -R 755 $WEB_ROOT
    chmod -R 777 $WEB_ROOT/uploads
    chmod -R 777 $WEB_ROOT/logs
    
    log_success "Estructura de directorios creada"
}

# Función para actualizar sistema
actualizar_sistema() {
    log_info "Actualizando sistema CentOS..."
    yum update -y
    yum install -y epel-release
    log_success "Sistema actualizado"
}

# Función para instalar Apache
instalar_apache() {
    log_info "Instalando y configurando Apache..."
    
    yum install -y httpd httpd-devel
    
    # Habilitar módulos
    echo "LoadModule rewrite_module modules/mod_rewrite.so" >> /etc/httpd/conf/httpd.conf
    
    # Configurar Virtual Host
    cat > /etc/httpd/conf.d/servicios.conf << EOF
<VirtualHost *:80>
    ServerName $DOMAIN
    DocumentRoot $WEB_ROOT/public
    
    <Directory $WEB_ROOT/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>
    
    ErrorLog $LOG_DIR/apache_error.log
    CustomLog $LOG_DIR/apache_access.log combined
</VirtualHost>
EOF
    
    systemctl enable httpd
    systemctl start httpd
    
    log_success "Apache instalado y configurado"
}

# Función para instalar PHP
instalar_php() {
    log_info "Instalando PHP y extensiones..."
    
    yum install -y php php-mysql php-gd php-ldap php-odbc php-pear php-xml \
                   php-xmlrpc php-mbstring php-snmp php-soap php-zip php-curl \
                   php-bcmath php-json php-session php-fileinfo
    
    # Configurar PHP
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' /etc/php.ini
    sed -i 's/post_max_size = 8M/post_max_size = 50M/' /etc/php.ini
    sed -i 's/max_execution_time = 30/max_execution_time = 300/' /etc/php.ini
    sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php.ini
    
    log_success "PHP instalado y configurado"
}

# Función para instalar MySQL/MariaDB
instalar_mysql() {
    log_info "Instalando y configurando MariaDB..."
    
    yum install -y mariadb-server mariadb
    
    systemctl enable mariadb
    systemctl start mariadb
    
    # Configuración segura básica
    mysql -e "UPDATE mysql.user SET Password = PASSWORD('$DB_PASS') WHERE User = 'root'"
    mysql -e "DELETE FROM mysql.user WHERE User=''"
    mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
    mysql -e "DROP DATABASE IF EXISTS test"
    mysql -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%'"
    mysql -e "FLUSH PRIVILEGES"
    
    # Crear base de datos y usuario
    mysql -u root -p$DB_PASS -e "crear DATABASE $DB_NAME CHARACTER SET utf8 COLLATE utf8_spanish_ci;"
    mysql -u root -p$DB_PASS -e "crear USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
    mysql -u root -p$DB_PASS -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
    mysql -u root -p$DB_PASS -e "FLUSH PRIVILEGES;"
    
    log_success "MariaDB instalado y configurado"
}

# Función para crear usuarios del sistema
crear_users() {
    log_info "Creando usuarios del sistema..."
    
    # Usuario administrador de la aplicación
    useradd -m -s /bin/bash $ADMIN_USER
    echo "$ADMIN_USER:$ADMIN_PASS" | chpasswd
    usermod -aG wheel $ADMIN_USER
    
    # Usuario para backups
    useradd -m -s /bin/bash backup_servicios
    echo "backup_servicios:$(openssl rand -base64 12)" | chpasswd
    
    log_success "Usuarios creados"
}

# Función para configurar variables de entorno
setup_environment() {
    log_info "Configurando variables de entorno..."
    
    cat > /opt/servicios/config/env.conf << EOF
# Configuración ServiciOS
SERVICIOS_ENV=production
SERVICIOS_DOMAIN=$DOMAIN
SERVICIOS_DB_HOST=localhost
SERVICIOS_DB_NAME=$DB_NAME
SERVICIOS_DB_USER=$DB_USER
SERVICIOS_DB_PASS=$DB_PASS
SERVICIOS_WEB_ROOT=$WEB_ROOT
SERVICIOS_BACKUP_DIR=$BACKUP_DIR
SERVICIOS_LOG_DIR=$LOG_DIR
SERVICIOS_UPLOAD_MAX_SIZE=50M
SERVICIOS_ADMIN_EMAIL=admin@$DOMAIN
EOF
    
    # Agregar al perfil del sistema
    echo "source /opt/servicios/config/env.conf" >> /etc/profile
    
    log_success "Variables de entorno configuradas"
}

# Función para configurar firewall
setup_firewall() {
    log_info "Configurando firewall..."
    
    systemctl enable firewalld
    systemctl start firewalld
    
    firewall-cmd --permanent --add-service=http
    firewall-cmd --permanent --add-service=https
    firewall-cmd --permanent --add-service=ssh
    firewall-cmd --reload
    
    log_success "Firewall configurado"
}

# Función para configurar backups automáticos
setup_backups() {
    log_info "Configurando sistema de backups..."
    
    # Script de backup
    cat > /opt/servicios/scripts/backup.sh << 'EOF'
#!/bin/bash
source /opt/servicios/config/env.conf

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$SERVICIOS_BACKUP_DIR/servicios_backup_$TIMESTAMP.tar.gz"
DB_BACKUP_FILE="$SERVICIOS_BACKUP_DIR/db_backup_$TIMESTAMP.sql"

# Backup de base de datos
mysqldump -u $SERVICIOS_DB_USER -p$SERVICIOS_DB_PASS $SERVICIOS_DB_NAME > $DB_BACKUP_FILE

# Backup de archivos
tar -czf $BACKUP_FILE -C / \
    var/www/html/servicios \
    opt/servicios/config \
    opt/servicios/uploads

# Limpiar backups antiguos (más de 7 días)
find $SERVICIOS_BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
find $SERVICIOS_BACKUP_DIR -name "*.sql" -mtime +7 -delete

echo "$(date): Backup completado - $BACKUP_FILE" >> $SERVICIOS_LOG_DIR/backup.log
EOF
    
    chmod +x /opt/servicios/scripts/backup.sh
    
    # Cron job para backups diarios
    echo "0 2 * * * /opt/servicios/scripts/backup.sh" | crontab -
    
    log_success "Sistema de backups configurado"
}

# Función para configurar red
configurar_network() {
    log_info "Configurando red del servidor..."
    
    # Obtener IP actual
    IP_ADDR=$(ip route get 8.8.8.8 | awk '/src/ {print $7}')
    
    # Agregar entrada al hosts
    echo "$IP_ADDR $DOMAIN" >> /etc/hosts
    
    # Configurar hostname
    hostnamectl set-hostname servicios-server
    
    log_success "Configuración de red completada"
}

# Función para crear estructura de base de datos
crear_database_structure() {
    log_info "Creando estructura de base de datos..."
    
    mysql -u $DB_USER -p$DB_PASS $DB_NAME << 'EOF'
-- Crear base de datos y usarla
CREATE DATABASE IF NOT EXISTS ServiciOs;
USE ServiciOs;

-- Tabla de Usuarios
CREATE TABLE Usuarios (
    ID_Usuarios INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    Apellido VARCHAR(50),
    Correo VARCHAR(100) UNIQUE,
    ContrasenaHash VARCHAR(255),
    Verificado BOOLEAN DEFAULT FALSE,
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_nombre_apellido ON Usuarios(Nombre, Apellido);

-- Tabla de Roles
CREATE TABLE Roles (
    ID_Rol INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50) UNIQUE
);

-- Tabla intermedia UsuarioRol
CREATE TABLE UsuarioRol (
    ID_Usuario INT,
    ID_Rol INT,
    PRIMARY KEY (ID_Usuario, ID_Rol),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Rol) REFERENCES Roles(ID_Rol)
);
CREATE INDEX idx_usuario ON UsuarioRol(ID_Usuario);
CREATE INDEX idx_rol ON UsuarioRol(ID_Rol);

-- Tabla de Categorías
CREATE TABLE Categorias (
    ID_Categoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50)
);
CREATE INDEX idx_categoria_nombre ON Categorias(Nombre);

-- Tabla de Subcategorías
CREATE TABLE Subcategorias (
    ID_Subcategoria INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(50),
    ID_Categoria INT,
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria)
);
CREATE INDEX idx_subcategoria_categoria ON Subcategorias(ID_Categoria);

-- Tabla de Zonas geográficas de cobertura
CREATE TABLE Zonas (
    ID_Zona INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100)
);
CREATE INDEX idx_zona_nombre ON Zonas(Nombre);

-- Tabla de Servicios
CREATE TABLE Servicios (
    ID_Servicio INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100),
    Descripcion TEXT,
    Precio DECIMAL(10,2),
    ID_Categoria INT,
    ID_Subcategoria INT,
    ID_Usuario INT,
    ID_Zona INT,
    FechaPublicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Categoria) REFERENCES Categorias(ID_Categoria),
    FOREIGN KEY (ID_Subcategoria) REFERENCES Subcategorias(ID_Subcategoria),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Zona) REFERENCES Zonas(ID_Zona)
);
CREATE INDEX idx_servicio_categoria ON Servicios(ID_Categoria);
CREATE INDEX idx_servicio_subcategoria ON Servicios(ID_Subcategoria);
CREATE INDEX idx_servicio_usuario ON Servicios(ID_Usuario);
CREATE INDEX idx_servicio_zona ON Servicios(ID_Zona);

-- Tabla de Solicitudes de servicios
CREATE TABLE Solicita (
    ID_Solicitud INT PRIMARY KEY AUTO_INCREMENT,
    ID_Cliente INT,
    ID_Servicio INT,
    Estado ENUM('Pendiente', 'Aceptada', 'Rechazada', 'Completada') DEFAULT 'Pendiente',
    FechaSolicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Servicio) REFERENCES Servicios(ID_Servicio)
);
CREATE INDEX idx_solicitud_cliente ON Solicita(ID_Cliente);
CREATE INDEX idx_solicitud_servicio ON Solicita(ID_Servicio);
CREATE INDEX idx_solicitud_estado ON Solicita(Estado);

-- Tabla de Valoraciones
CREATE TABLE Valoraciones (
    ID_Valor INT PRIMARY KEY AUTO_INCREMENT,
    ID_Cliente INT,
    ID_Proveedor INT,
    Puntos INT CHECK (Puntos BETWEEN 1 AND 5),
    Comentario TEXT,
    FechaValoracion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Proveedor) REFERENCES Usuarios(ID_Usuarios)
);
CREATE INDEX idx_valoracion_cliente ON Valoraciones(ID_Cliente);
CREATE INDEX idx_valoracion_proveedor ON Valoraciones(ID_Proveedor);

-- Tabla de Mensajes entre usuarios
CREATE TABLE Mensajes (
    ID_Mensaje INT PRIMARY KEY AUTO_INCREMENT,
    ID_Cliente INT,
    ID_Proveedor INT,
    Contenido TEXT,
    FechaHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Cliente) REFERENCES Usuarios(ID_Usuarios),
    FOREIGN KEY (ID_Proveedor) REFERENCES Usuarios(ID_Usuarios)
);
CREATE INDEX idx_mensaje_cliente ON Mensajes(ID_Cliente);
CREATE INDEX idx_mensaje_proveedor ON Mensajes(ID_Proveedor);

-- Tabla de Portafolio de trabajos realizados
CREATE TABLE Portafolio (
    ID_Portafolio INT PRIMARY KEY AUTO_INCREMENT,
    ID_Usuario INT,
    Titulo VARCHAR(100),
    Descripcion TEXT,
    Imagen VARCHAR(255),
    FechaSubida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuarios)
);
CREATE INDEX idx_portafolio_usuario ON Portafolio(ID_Usuario);


INSERT INTO Roles (Nombre) VALUES ('cliente');
INSERT INTO Roles (Nombre) VALUES ('proveedor');
INSERT INTO Roles (Nombre) VALUES ('admin');

INSERT INTO UsuarioRol (ID_Usuario, ID_Rol) VALUES (3, 1);

-- Insertar categorías por defecto
INSERT INTO categorias (nombre, descripcion, icono) VALUES
('Plomería', 'Servicios de instalación y reparación de plomería', 'wrench'),
('Electricidad', 'Instalaciones y reparaciones eléctricas', 'zap'),
('Carpintería', 'Trabajos en madera y muebles', 'hammer'),
('Limpieza', 'Servicios de limpieza doméstica y comercial', 'sparkles'),
('Jardinería', 'Mantenimiento de jardines y espacios verdes', 'leaf'),
('Pintura', 'Servicios de pintura interior y exterior', 'palette');

-- Crear usuario administrador por defecto
INSERT INTO usuarios (email, password, nombre, apellido, tipo_usuario) 
VALUES ('admin@servicios.local', MD5('admin123'), 'Administrador', 'Sistema', 'admin');
EOF
    
    log_success "Estructura de base de datos creada"
}

# Función principal de instalación
main() {
    echo "========================================="
    echo "    ServiciOS - Instalación Servidor    "
    echo "========================================="
    echo
    
    verificar_root
    
    log_info "Iniciando instalación de ServiciOS..."
    
    crear_directorios
    actualizar_sistema
    instalar_apache
    instalar_php
    instalar_mysql
    crear_users
    setup_environment
    configurar_network
    setup_firewall
    setup_backups
    crear_database_structure
    
    # Reiniciar servicios
    systemctl restart httpd
    systemctl restart mariadb
    
    # Mostrar información de instalación
    echo
    echo "========================================="
    echo "    INSTALACIÓN COMPLETADA             "
    echo "========================================="
    echo "Dominio: $DOMAIN"
    echo "IP del servidor: $(ip route get 8.8.8.8 | awk '/src/ {print $7}')"
    echo "Base de datos: $DB_NAME"
    echo "Usuario DB: $DB_USER"
    echo "Password DB: $DB_PASS"
    echo "Usuario Admin Sistema: $ADMIN_USER"
    echo "Password Admin Sistema: $ADMIN_PASS"
    echo "Directorio Web: $WEB_ROOT"
    echo "Directorio Backups: $BACKUP_DIR"
    echo "========================================="
    echo
    echo "Para acceder: http://$DOMAIN"
    echo "Login admin: admin@servicios.local / admin123"
    echo
    echo "Logs en: $LOG_DIR"
    echo "Configuración en: /opt/servicios/config/"
    echo
    
    log_success "Instalación de ServiciOS completada exitosamente"
}

# Verificar argumentos
case "$1" in
    install)
        main
        ;;
    *)
        echo "Uso: $0 {install}"
        echo
        echo "Comandos disponibles:"
        echo "  install    - Instala y configura el servidor completo"
        echo
        exit 1
        ;;
esac
