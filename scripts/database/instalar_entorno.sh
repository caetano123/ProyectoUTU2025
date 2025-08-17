#!/bin/bash

# Script de instalación y configuración del entorno "ServiciOS"
# Requiere permisos de superusuario

APP_DIR="/var/www/html"
APP_USER="servicios_user"
DB_ROOT_PASS="RootPass123"
DB_APP_PASS="AppPass123"

# Función para verificar si se ejecuta como root
verificar_root() {
    if [ "$EUID" -ne 0 ]; then
        echo "Este script debe ejecutarse como root." >&2
        exit 1
    fi
}

# Actualización del sistema
actualizar_sistema() {
    echo "Actualizando el sistema."
    dnf update -y
}

# Instalación de paquetes necesarios
instalar_paquetes() {
    echo "Instalando Apache, PHP, MariaDB    dnf install -y httpd php php-mysqlnd php-cli php-gd php-mbstring php-xml mariadb-server mariadb git unzip
}

# Habilitación de servicios
habilitar_servicios() {
    systemctl enable --now httpd
    systemctl enable --now mariadb
    firewall-cmd --add-service=http --permanent
    firewall-cmd --reload
}

# Creación de usuario del sistema
crear_usuario_app() {
    echo "👤 Creando usuario del sistema '$APP_USER'..."
    id "$APP_USER" &>/dev/null || useradd -m -s /bin/bash "$APP_USER"
}

# Configuración de directorios
configurar_directorios() {
    echo "📁 Configurando estructura de directorios..."
    mkdir -p "$APP_DIR"/{public,logs,backups}
    chown -R "$APP_USER":"$APP_USER" "$APP_DIR"
    chmod -R 755 "$APP_DIR"
}

# Configuración de MySQL
configurar_mysql() {
    echo "🛢️ Configurando base de datos MySQL..."
    systemctl start mariadb
    mysql -u root <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED BY '$DB_ROOT_PASS';
CREATE DATABASE ServiciOs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'servicios_user'@'localhost' IDENTIFIED BY '$DB_APP_PASS';
GRANT ALL PRIVILEGES ON ServiciOs.* TO 'servicios_user'@'localhost';
FLUSH PRIVILEGES;
EOF
}

# Configuración de Apache y VirtualHost
configurar_apache() {
    echo "🌐 Configurando VirtualHost de Apache..."

    cat > /etc/httpd/conf.d/servicios.conf <<EOF
<VirtualHost *:80>
    ServerName servicios.local
    DocumentRoot $APP_DIR/public

    <Directory $APP_DIR/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog $APP_DIR/logs/error.log
    CustomLog $APP_DIR/logs/access.log combined
</VirtualHost>
EOF

    sed -i 's/AllowOverride None/AllowOverride All/' /etc/httpd/conf/httpd.conf

    echo "127.0.0.1 servicios.local" >> /etc/hosts

    systemctl restart httpd
}

# Variables de entorno (ejemplo)
configurar_variables_entorno() {
    echo "🌱 Configurando variables de entorno..."
    echo "export APP_ENV=production" >> /etc/profile.d/servicios_env.sh
    echo "export APP_DIR=$APP_DIR" >> /etc/profile.d/servicios_env.sh
    chmod +x /etc/profile.d/servicios_env.sh
}

# Crear archivo index de prueba
crear_index() {
    echo "<?php phpinfo(); ?>" > "$APP_DIR/public/index.php"
    chown "$APP_USER":"$APP_USER" "$APP_DIR/public/index.php"
}

# Ejecutar todo
main() {
    verificar_root
    actualizar_sistema
    instalar_paquetes
    habilitar_servicios
    crear_usuario_app
    configurar_directorios
    configurar_mysql
    configurar_apache
    configurar_variables_entorno
    crear_index

    echo "✅ Instalación completa. Visitá http://servicios.local para verificar."
}

main
