#!/bin/bash
# Sistema de gestión de usuarios y grupos ABM

# Funciones para dar mensajes
log() {
    echo "$2$1"
}

log_error() { log "$1" "[ERROR] "; }
log_exitoso() { log "$1" "[OK] "; }
log_info() { log "$1" "[INFO] "; }

# Verificar permisos de root
verificar_root() {
    if [ "$EUID" -ne 0 ]; then
        log_error "Se requieren permisos de root"
        exit 1
    fi
}

# Menú principal
mostrar_menu() {
    clear
    echo -e " === Gestión de Usuarios === "
    echo
    echo "1. Crear usuario"
    echo "2. Eliminar usuario"
    echo "3. Listar usuarios"
    echo "4. Cambiar contraseña"
    echo "5. Crear grupo"
    echo "6. Agregar usuario a grupo"
    echo "7. Listar grupos"
    echo "8. Salir"
    echo
    read -p "Opción [1-8]: " choice
}

# Crear usuario
crear_usuario() {
    clear
    echo -e " === Crear Usuario === "
    
    read -p "Nombre de usuario: " username
    read -p "Nombre completo: " fullname
    read -s -p "Contraseña: " password
    echo
    
    if [ -z "$username" ] || [ -z "$password" ]; then
        log_error "Nombre y contraseña son obligatorios"
        return 1
    fi
    
    if id "$username" &>/dev/null; then
        log_error "El usuario '$username' ya existe"
        return 1
    fi
    
    useradd -m -c "$fullname" -s /bin/bash "$username"
    echo "$username:$password" | chpasswd
    
    if [ $? -eq 0 ]; then
        log_exitoso "Usuario '$username' creado exitosamente"
    else
        log_error "Error al crear el usuario"
    fi
}

# Eliminar usuario
eliminar_usuario() {
    clear
    echo -e " === Eliminar Usuario === "
    
    read -p "Nombre del usuario: " username
    
    if ! id "$username" &>/dev/null; then
        log_error "El usuario '$username' no existe"
        return 1
    fi
    
    read -p "¿Eliminar directorio home? (y/n): " eliminar_home
    read -p "¿Confirma eliminación de '$username'? (y/n): " confirm
    
    if [[ "$confirm" =~ ^[Yy]$ ]]; then
        if [[ "$eliminar_home" =~ ^[Yy]$ ]]; then
            userdel -r "$username"
        else
            userdel "$username"
        fi
        log_exitoso "Usuario '$username' eliminado"
    else
        log_info "Operación cancelada"
    fi
}

# Listar usuarios
listar_usuarios() {
    clear
    echo -e " === Lista de Usuarios === "
    echo
    printf "%-15s %-10s %-30s\n" "Usuario" "UID" "Nombre"
    echo "------------------------------------------------"
    awk -F: '$3 >= 1000 {printf "%-15s %-10s %-30s\n", $1, $3, $5}' /etc/passwd
    echo
    echo "Total: $(awk -F: '$3 >= 1000' /etc/passwd | wc -l) usuarios"
}

# Cambiar contraseña
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

# Crear grupo
crear_grupo() {
    clear
    echo -e " === Crear Grupo === "
    
    read -p "Nombre del grupo: " groupname
    
    if [ -z "$groupname" ]; then
        log_error "El nombre del grupo es obligatorio"
        return 1
    fi
    
    if getent group "$groupname" >/dev/null 2>&1; then
        log_error "El grupo '$groupname' ya existe"
        return 1
    fi
    
    groupadd "$groupname"
    log_exitoso "Grupo '$groupname' creado exitosamente"
}

# Agregar usuario a grupo
aregar_usuario_a_grupo() {
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

# Listar grupos
listar_grupos() {
    clear
    echo -e " === Lista de Grupos === "
    echo
    printf "%-20s %-10s %-30s\n" "Grupo" "GID" "Miembros"
    echo "--------------------------------------------------------"
    while IFS=: read -r group x gid members; do
        if [ $gid -ge 1000 ] || [[ "$group" =~ ^(wheel|users|sudo)$ ]]; then
            printf "%-20s %-10s %-30s\n" "$group" "$gid" "${members:0:30}"
        fi
    done < /etc/group
}

# Función principal
main() {
    verificar_root
    
    while true; do
        mostrar_menu
        
        case $choice in
            1) crear_usuario ;;
            2) eliminar_usuario ;;
            3) listar_usuarios ;;
            4) cambiar_contrasena ;;
            5) crear_grupo ;;
            6) aregar_usuario_a_grupo ;;
            7) listar_grupos ;;
            8) 
                log_info "Chau."
                exit 0
                ;;
            *)
                log_error "Opción no válida"
                ;;
        esac
        echo
        read -p "Presione Enter para continuar..."
    done
}

# Ejecutar
main "$@"
