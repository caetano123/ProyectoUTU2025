#!/bin/bash

set -e

USUARIO_APP="serviciosuser"
GRUPO_APP="serviciosgroup"
DIR_PROYECTO="/opt/ProyectoUTU2025"
REPO_GIT="https://github.com/caetano123/ProyectoUTU2025.git"
DIR_BACKUP="/opt/backups"
APACHE_CONF="/etc/httpd/conf.d/proyecto.conf"
ENV_FILE="$DIR_PROYECTO/.env"

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

# Validar conexión nmcli (loop hasta válida)
leer_conexion_nmcli() {
  while true; do
    echo "Conexiones disponibles:"
    nmcli con show | awk 'NR>1 {print NR-1 ") "$1" ("$2")"}'
    read -p "Nombre exacto de la conexión a configurar: " CONEXION
    if nmcli con show "$CONEXION" &>/dev/null; then
      echo "$CONEXION"
      return
    else
      echo "Conexión '$CONEXION' no encontrada. Intenta otra vez."
    fi
  done
}

# Validar IP con máscara básica
leer_ip_con_mask() {
  local ip
  while true; do
    read -p "Dirección IP con máscara (ej: 192.168.1.100/24): " ip
    if [[ "$ip" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}/[0-9]{1,2}$ ]]; then
      echo "$ip"
      return
    else
      echo "Formato inválido. Debe ser algo como 192.168.1.100/24."
    fi
  done
}

# Validar IP simple (puede usarse para gateway y DNS)
leer_ip_simple() {
  local ip
  while true; do
    read -p "$1" ip
    if [[ "$ip" =~ ^([0-9]{1,3}\.){3}[0-9]{1,3}$ ]]; then
      echo "$ip"
      return
    else
      echo "Formato inválido. Debe ser una IP como 192.168.1.1."
    fi
  done
}

# --- Funciones originales con validaciones integradas ---

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

crear_carpetas() {
    echo "==> Creando directorios para backups y logs..."
    mkdir -p "$DIR_BACKUP"
    chown "$USUARIO_APP":"$GRUPO_APP" "$DIR_BACKUP"
    chmod 770 "$DIR_BACKUP"
    mkdir -p "$APACHE_LOG_DIR"
    echo "Directorios creados y permisos asignados."
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
}

configurar_apache() {
    echo "==> Instalando Apache y configurando VirtualHost..."

    yum install -y httpd php php-mysql mariadb-server php-mbstring php-pdo

    systemctl enable httpd
    systemctl start httpd

    cat <<EOF > "$APACHE_CONF"
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot $DIR_PROYECTO/public

    <Directory $DIR_PROYECTO/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog $APACHE_LOG_DIR/proyecto_error.log
    CustomLog $APACHE_LOG_DIR/proyecto_access.log combined
</VirtualHost>
EOF

    systemctl restart httpd

    echo "Apache configurado para servir $DIR_PROYECTO/public"
}

configurar_red() {
    echo "==> Configuración de red estática usando nmcli"

    CONEXION=$(leer_conexion_nmcli)
    IP=$(leer_ip_con_mask)
    GATEWAY=$(leer_ip_simple "Puerta de enlace (ej: 192.168.1.1): ")
    DNS1=$(leer_ip_simple "DNS primario (ej: 8.8.8.8): ")

    echo "Aplicando configuración..."

    nmcli con mod "$CONEXION" ipv4.addresses "$IP"
    nmcli con mod "$CONEXION" ipv4.gateway "$GATEWAY"
    nmcli con mod "$CONEXION" ipv4.dns "$DNS1"
    nmcli con mod "$CONEXION" ipv4.method manual
    nmcli con up "$CONEXION"

    echo "Red configurada con IP estática $IP en conexión '$CONEXION'."
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

                mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$DB_DATABASE" -e "
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

                mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$DB_DATABASE" -e "
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
                        mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$DB_DATABASE" -e "
                        UPDATE Usuarios SET Nombre = '$valor' WHERE Correo = '$correo';
                        "
                        echo "Nombre modificado."
                        ;;
                    2)
                        valor=$(leer_no_vacio "Nuevo apellido: ")
                        mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$DB_DATABASE" -e "
                        UPDATE Usuarios SET Apellido = '$valor' WHERE Correo = '$correo';
                        "
                        echo "Apellido modificado."
                        ;;
                    3)
                        valor=$(leer_no_vacio "Nuevo correo: ")
                        mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$DB_DATABASE" -e "
                        UPDATE Usuarios SET Correo = '$valor' WHERE Correo = '$correo';
                        "
                        echo "Correo modificado."
                        ;;
                esac
                ;;
            4)
                echo "Usuarios en BD:"
                mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -D "$DB_DATABASE" -e "
                SELECT ID_Usuarios, Nombre, Apellido, Correo, Verificado, FechaRegistro FROM Usuarios;
                "
                ;;
            5)
                break
                ;;
        esac
    done
}

# Menú principal
while true; do
    echo ""
    echo "=== ADMINISTRACIÓN DE SERVIDOR ==="
    echo "1) Crear usuario, grupo y carpetas"
    echo "2) Clonar o actualizar proyecto desde Git"
    echo "3) Instalar y configurar Apache + PHP + MySQL"
    echo "4) Configurar red estática"
    echo "5) ABM usuarios y grupos"
    echo "6) Salir"
    opcion=$(leer_opcion "Elige opción: " 1 6)

    case $opcion in
        1) crear_usuario_grupo; crear_carpetas ;;
        2) clonar_actualizar_repo ;;
        3) configurar_apache ;;
        4) configurar_red ;;
        5) abm_usuarios_bd ;;
        6) echo "Saliendo..."; exit 0 ;;
    esac
done
