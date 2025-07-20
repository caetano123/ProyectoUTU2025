#!/bin/bash

# Variables fijas (por defecto)
USUARIO_APP="serviciosuser"
GRUPO_APP="serviciosgroup"
DIR_PROYECTO="/opt/ProyectoUTU2025"
REPO_GIT="https://github.com/caetano123/ProyectoUTU2025.git"
APACHE_CONF="/etc/httpd/conf.d"
APACHE_LOG_DIR="/var/log/httpd"
ENV_FILE="$DIR_PROYECTO/.env"


RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # Sin color


# Cargar variables del .env si existe
if [ -f "$ENV_FILE" ]; then
    set -o allexport
    source "$ENV_FILE"
    set +o allexport
else
    echo "[WARNING] No se encontró archivo .env en $ENV_FILE"
fi


# Funciones de utilidad
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1" | tee -a $APACHE_LOG_DIR/install.log
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a $APACHE_LOG_DIR/install.log
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a $APACHE_LOG_DIR/install.log
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a $APACHE_LOG_DIR/install.log
}

verificar_root() {
    if [ "$EUID" -ne 0 ]; then
        echo "Este script debe ejecutarse como root."
        exit 1
    fi
}


# Helper para leer opción válida de un rango numérico
leer_opcion() {
  local prompt="$1"
  local min="$2"
  local max="$3"
  local opcion
  while true; do
    read -p "$prompt" opcion
    if [[ "$opcion" =~ ^[0-9]+$ ]] && (( opcion >= min && opcion <= max )); then
      echo "$opcion"
      return
    else
      echo "Entrada inválida. Por favor ingresa un número entre $min y $max."
    fi
  done
}

# Helper para leer string no vacío
leer_no_vacio() {
  local prompt="$1"
  local entrada
  while true; do
    read -p "$prompt" entrada
    if [[ -n "$entrada" ]]; then
      echo "$entrada"
      return
    else
      echo "La entrada no puede estar vacía."
    fi
  done
}

# Validar IP con máscara CIDR (ej: 192.168.1.100/24)
leer_ip_con_mask() {
  while true; do
    read -p "Ingrese la IP con máscara (ej: 192.168.1.100/24): " ip
    if [[ "$ip" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}/[0-9]{1,2}$ ]]; then
      echo "$ip"
      return
    else
      echo "Formato inválido. Debe ser algo como 192.168.1.100/24."
    fi
  done
}

# Función para listar conexiones nmcli y leer conexión válida
leer_conexion_nmcli() {
  while true; do
    echo "Conexiones disponibles:"
    nmcli con show | awk 'NR>1 {print NR-1 ") " $1 " (" $2 ")"}'
    read -p "Ingrese el nombre exacto de la conexión a configurar: " CONEXION
    if nmcli con show "$CONEXION" &>/dev/null; then
      echo "$CONEXION"
      return
    else
      echo "Conexión '$CONEXION' no encontrada. Intente nuevamente."
    fi
  done
}

# Validar IP simple (ej: gateway, DNS)
leer_ip_simple() {
  local prompt="$1"
  while true; do
    read -p "$prompt" ip
    if [[ "$ip" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}$ ]]; then
      echo "$ip"
      return
    else
      echo "Formato inválido. Debe ser una IP válida como 192.168.1.1."
    fi
  done
}

configurar_red() {
  echo "==> Configuración de red estática con nmcli"

  CONEXION=$(leer_conexion_nmcli)
  IP=$(leer_ip_con_mask)
  GATEWAY=$(leer_ip_simple "Ingrese puerta de enlace (gateway) (ej: 192.168.1.1): ")
  DNS=$(leer_ip_simple "Ingrese DNS primario (ej: 8.8.8.8): ")

  echo "Aplicando configuración..."
  nmcli con mod "$CONEXION" ipv4.addresses "$IP"
  nmcli con mod "$CONEXION" ipv4.gateway "$GATEWAY"
  nmcli con mod "$CONEXION" ipv4.dns "$DNS"
  nmcli con mod "$CONEXION" ipv4.method manual
  nmcli con up "$CONEXION"

  echo "Red configurada con IP estática $IP en la conexión '$CONEXION'."
}

crear_usuario_grupo() {
    echo "==> Creando grupo y usuario..."
    if ! getent group "$GRUPO_APP" >/dev/null; then
        groupadd "$GRUPO_APP"
        echo "Grupo $GRUPO_APP creado."
    else
        echo "Grupo $GRUPO_APP ya existe."
    fi

    if ! id "$USUARIO_APP" &>/dev/null; then
        useradd -m -g "$GRUPO_APP" "$USUARIO_APP"
        echo "$USUARIO_APP:1234" | chpasswd
        echo "Usuario $USUARIO_APP creado con contraseña por defecto 1234."
    else
        echo "Usuario $USUARIO_APP ya existe."
    fi
}

cambiar_contrasena() {
    clear
    echo -e " === Cambiar Contraseña === "
    
    read -p "Nombre del usuario: " username
    
    if ! id "$username" &>/dev/null; then
        log_error "El usuario '$username' no existe"
        return 1
    fi
    
    passwd "$username"
    log_exitoso "Contraseña actualizada para '$username'"
}

agregar_usuario_a_grupo() {
    clear
    echo -e " === Agregar Usuario a Grupo === "
    
    read -p "Nombre del usuario: " username
    read -p "Nombre del grupo: " groupname
    
    if ! id "$username" &>/dev/null; then
        log_error "El usuario '$username' no existe"
        return 1
    fi
    
    if ! getent group "$groupname" >/dev/null 2>&1; then
        log_error "El grupo '$groupname' no existe"
        return 1
    fi
    
    usermod -aG "$groupname" "$username"
    log_exitoso "Usuario '$username' agregado al grupo '$groupname'"
}

abm_usuarios_bd() {
    if [ ! -f "$ENV_FILE" ]; then
      echo "Archivo $ENV_FILE no encontrado"
      return 1
    fi

    set -o allexport
    source "$ENV_FILE"
    set +o allexport

    while true; do
        echo ""
        echo "=== Menú ABM Usuarios (SO + MySQL) ==="
        echo "1) Crear usuario (SO + BD)"
        echo "2) Eliminar usuario (SO + BD)"
        echo "3) Modificar usuario (BD)"
        echo "4) Listar usuarios (BD)"
        echo "5) Volver"
        op=$(leer_opcion "Elige opción: " 1 5)

        case $op in
            1)
                username=$(leer_no_vacio "Nombre usuario SO: ")
                nombre=$(leer_no_vacio "Nombre (BD): ")
                apellido=$(leer_no_vacio "Apellido (BD): ")
                correo=$(leer_no_vacio "Correo (BD): ")
                read -sp "Contraseña: " pass
                echo

                if id "$username" &>/dev/null; then
                    echo "Usuario SO $username ya existe."
                else
                    useradd -m "$username"
                    echo "$username:$pass" | chpasswd
                    echo "Usuario SO $username creado."
                fi

                pass_hash=$(echo -n "$pass" | sha256sum | awk '{print $1}')

                mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$SERVICIOS_DB_DATABASE" -e "
                INSERT INTO Usuarios (Nombre, Apellido, Correo, ContrasenaHash) VALUES
                ('$nombre', '$apellido', '$correo', '$pass_hash');
                "
                echo "Usuario creado en BD."
                ;;
            2)
                username=$(leer_no_vacio "Nombre usuario SO a eliminar: ")
                correo=$(leer_no_vacio "Correo usuario BD a eliminar: ")

                if id "$username" &>/dev/null; then
                    userdel -r "$username"
                    echo "Usuario SO $username eliminado."
                else
                    echo "Usuario SO $username no existe."
                fi

                mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$SERVICIOS_DB_DATABASE" -e "
                DELETE FROM Usuarios WHERE Correo = '$correo';
                "
                echo "Usuario eliminado de BD."
                ;;
            3)
                correo=$(leer_no_vacio "Correo usuario a modificar: ")
                echo "Campos a modificar:"
                echo "1) Nombre"
                echo "2) Apellido"
                echo "3) Correo"
                campo=$(leer_opcion "Elige campo: " 1 3)

                case $campo in
                    1)
                        valor=$(leer_no_vacio "Nuevo nombre: ")
                        mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$SERVICIOS_DB_DATABASE" -e "
                        UPDATE Usuarios SET Nombre = '$valor' WHERE Correo = '$correo';
                        "
                        echo "Nombre modificado."
                        ;;
                    2)
                        valor=$(leer_no_vacio "Nuevo apellido: ")
                        mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$SERVICIOS_DB_DATABASE" -e "
                        UPDATE Usuarios SET Apellido = '$valor' WHERE Correo = '$correo';
                        "
                        echo "Apellido modificado."
                        ;;
                    3)
                        valor=$(leer_no_vacio "Nuevo correo: ")
                        mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$SERVICIOS_DB_DATABASE" -e "
                        UPDATE Usuarios SET Correo = '$valor' WHERE Correo = '$correo';
                        "
                        echo "Correo modificado."
                        ;;
                esac
                ;;
            4)
                echo "Usuarios en BD:"
                mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$SERVICIOS_DB_DATABASE" -e "
                SELECT ID_Usuarios, Nombre, Apellido, Correo, Verificado, FechaRegistro FROM Usuarios;
                "
                ;;
            5)
                break
                ;;
        esac
    done
}


actualizar_sistema() {
    log_info "Actualizando sistema CentOS..."
    yum update -y
    yum install -y epel-release
    log_success "Sistema actualizado"
}


instalar_apache() {
    log_info "Instalando y configurando Apache..."

    yum install -y httpd php php-mysqlnd

    # Crear dir de logs si no existe
    mkdir -p "$APACHE_LOG_DIR"

    CONF="/etc/httpd/conf/httpd.conf"

    # Hacer backup antes de modificar
    cp "$CONF" "$CONF.bak"

    # Cambiar el DocumentRoot solo si es el principal (evita los VirtualHost)
    sed -i "0,/^DocumentRoot/s|^DocumentRoot \".*\"|DocumentRoot \"$DIR_PROYECTO/public\"|" "$CONF"

    # Asegurarse de que exista el bloque <Directory "$DIR_PROYECTO/public"> correctamente configurado
    if ! grep -q "<Directory \"$DIR_PROYECTO/public\">" "$CONF"; then
        cat <<EOF >> "$CONF"

<Directory "$DIR_PROYECTO/public">
    AllowOverride All
    Require all granted
    Options -Indexes +FollowSymLinks
</Directory>
EOF
    else
        log_info "Ya existe el bloque <Directory \"$DIR_PROYECTO/public\">, no se duplica."
    fi

    # Asegurar que mod_rewrite esté cargado
    if ! grep -E '^LoadModule rewrite_module' "$CONF"; then
        echo "LoadModule rewrite_module modules/mod_rewrite.so" >> "$CONF"
    fi

    systemctl enable httpd
    systemctl restart httpd

    log_success "Apache instalado y configurado (sin VirtualHost)"
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
    mysql -u root -p"$DB_PASSWORD" -e "UPDATE mysql.user SET authentication_string=PASSWORD('$DB_PASSWORD') WHERE User='root';"
    mysql -u root -p"$DB_PASSWORD" -e "DELETE FROM mysql.user WHERE User='';"
    mysql -u root -p"$DB_PASSWORD" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
    mysql -u root -p"$DB_PASSWORD" -e "DROP DATABASE IF EXISTS test;"
    mysql -u root -p"$DB_PASSWORD" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
    mysql -u root -p"$DB_PASSWORD" -e "FLUSH PRIVILEGES;"

    # Crear base de datos si no existe
    mysql -u root -p"$DB_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $SERVICIOS_DB_DATABASE CHARACTER SET utf8 COLLATE utf8_spanish_ci;"

     mysql -u root -p"$DB_PASSWORD" -e "CREATE USER '$SERVICIOS_DB_USER'@'localhost' IDENTIFIED BY '$SERVICIOS_DB_PASS';"
     mysql -u root -p"$DB_PASSWORD" -e "GRANT ALL PRIVILEGES ON $SERVICIOS_DB_DATABASE.* TO '$SERVICIOS_DB_USER'@'localhost';"
     mysql -u root -p"$DB_PASSWORD" -e "FLUSH PRIVILEGES;"

    log_success "MariaDB instalado y configurado"
}


# Función para crear estructura de base de datos
crear_database_structure() {
    log_info "Creando estructura de base de datos..."
    
    mysql -u "$SERVICIOS_DB_USER" -p"$SERVICIOS_DB_PASS" "$SERVICIOS_DB_DATABASE" << 'EOF'
-- Crear base de datos y seleccionarla
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

-- Roles base
INSERT IGNORE INTO Roles (Nombre) VALUES ('cliente'), ('proveedor'), ('admin');
EOF

    if [ $? -eq 0 ]; then
        log_success "[OK] Base de datos ServiciOs creada correctamente."
    else
        log_error "[ERROR] Hubo un problema al crear la base de datos."
    fi
}

clonar_actualizar_repo() {
    echo "==> Clonando o actualizando proyecto desde Git..."
    yum install -y git

    if [ -d "$DIR_PROYECTO/.git" ]; then
        echo "Repositorio ya existe, actualizando..."
        cd "$DIR_PROYECTO"
        git pull
    else
        git clone "$REPO_GIT" "$DIR_PROYECTO"
    fi

    chown -R "$USUARIO_APP":"$GRUPO_APP" "$DIR_PROYECTO"
    echo "Repositorio listo en $DIR_PROYECTO"

    # === Crear enlace simbólico ===
    echo "==> Verificando enlace simbólico en /var/www/html/public..."

    # Borra el enlace simbólico o carpeta actual si existe
    if [ -L /var/www/html/public ] || [ -d /var/www/html/public ]; then
        rm -rf /var/www/html/public
        echo "Enlace o carpeta antigua eliminada."
    fi

    # Crear nuevo enlace simbólico
    ln -s "$DIR_PROYECTO/app/public" /var/www/html/public
    echo "Enlace simbólico creado: /var/www/html/public -> $DIR_PROYECTO/app/public"
}


# Configuración de Apache (opcional, comentada por ahora)
# configurar_apache() {
#    echo "==> Instalando Apache y configurando VirtualHost..."

#    yum install -y httpd php php-mysql mariadb-server php-mbstring php-pdo

#    systemctl enable httpd
#    systemctl start httpd

#   cat <<EOF > "$APACHE_CONF"
#<VirtualHost *:80>
#    ServerName localhost
#    DocumentRoot $DIR_PROYECTO/public

#    <Directory $DIR_PROYECTO/public>
#        Options Indexes FollowSymLinks
#        AllowOverride All
#        Require all granted
#    </Directory>

#    ErrorLog $APACHE_LOG_DIR/proyecto_error.log
#    CustomLog $APACHE_LOG_DIR/proyecto_access.log combined
#</VirtualHost>
#EOF

#    systemctl restart httpd

#    echo "Apache configurado para servir $DIR_PROYECTO/public"
# }


setup_backups() {
    log_info "Configurando sistema de backups..."
    
    # Crear directorio de scripts si no existe
    mkdir -p /opt/ProyectoUTU2025/scripts

    cat > /opt/ProyectoUTU2025/scripts/backups.sh << 'EOF'
#!/bin/bash
source /opt/ProyectoUTU2025/.env

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$SERVICIOS_BACKUP_DIR/servicios_backup_$TIMESTAMP.tar.gz"
DB_BACKUP_FILE="$SERVICIOS_BACKUP_DIR/db_backup_$TIMESTAMP.sql"

# Backup de base de datos
mysqldump -u "$SERVICIOS_DB_USER" -p"$SERVICIOS_DB_PASS" "$SERVICIOS_DB_DATABASE" > "$DB_BACKUP_FILE"


# Backup de archivos
tar -czf "$BACKUP_FILE" -C / \
    var/www/html/servicios \
    opt/servicios/config \
    opt/servicios/uploads

# Limpiar backups antiguos (más de 7 días)
find "$SERVICIOS_BACKUP_DIR" -name "*.tar.gz" -mtime +7 -delete
find "$SERVICIOS_BACKUP_DIR" -name "*.sql" -mtime +7 -delete

echo "$(date): Backup completado - $BACKUP_FILE" >> "$SERVICIOS_LOG_DIR/backup.log"
EOF

    chmod +x /opt/ProyectoUTU2025/scripts/backups.sh

    # Agregar la tarea al crontab si no existe ya (sin borrar otras tareas)
    (crontab -l 2>/dev/null | grep -q '/opt/ProyectoUTU2025/scripts/backups.sh') || \
    (crontab -l 2>/dev/null; echo "0 2 * * * /opt/ProyectoUTU2025/scripts/backups.sh") | crontab -

    log_success "Sistema de backups configurado"
}


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

#!/bin/bash
# Script maestro de instalación y administración del sistema ServiciOS

set -e

verificar_root

while true; do
    echo "========= MENÚ PRINCIPAL ========="
    echo "1. Actualizar sistema"
    echo "2. Instalar servicios base (Apache, PHP, MariaDB)"
    echo "3. Clonar o actualizar repositorio del proyecto"
    echo "4. Configurar red manualmente (IP estática)"
    echo "5. Configurar firewall"
    echo "6. Crear estructura base de base de datos"
    echo "7. Gestión de usuarios del sistema"
    echo "8. Gestión de usuarios en base de datos"
    echo "9. Configurar backups automáticos"
    echo "10. Salir"
    echo "==================================="
    read -p "Seleccione una opción: " opcion

    case $opcion in
        1) actualizar_sistema ;;
        2) instalar_apache; instalar_php; instalar_mysql ;;
        3) clonar_actualizar_repo ;;
        4) configurar_red ;;
        5) setup_firewall ;;
        6) crear_database_structure ;;
        7)
            echo "  --- Gestión de usuarios del sistema ---"
            echo "  a) Crear usuario y grupo"
            echo "  b) Cambiar contraseña"
            echo "  c) Agregar usuario a grupo"
            read -p "  Seleccione una opción: " subop
            case $subop in
                a) crear_usuario_grupo ;;
                b) cambiar_contrasena ;;
                c) agregar_usuario_a_grupo ;;
                *) echo "Opción no válida" ;;
            esac
            ;;
        8) abm_usuarios_bd ;;
        9) setup_backups ;;
        10) echo "Saliendo..."; exit 0 ;;
        *) echo "Opción no válida" ;;
    esac
done

