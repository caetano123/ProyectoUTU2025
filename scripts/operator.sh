#!/usr/bin/env bash
# operator.sh - script modular de operaciones
set -euo pipefail

APP_DIR="/opt/ProyectoUTU2025"
SERVICE_NAME="httpd"       # apache o nginx
DB_SERVICE="mysql"

log() { echo "[$(date +'%F %T')] $*"; }

check_services() {
  log "Comprobando servicios..."
  systemctl is-active --quiet "${SERVICE_NAME}" && echo "${SERVICE_NAME}: activo" || echo "${SERVICE_NAME}: inactivo"
  systemctl is-active --quiet "${DB_SERVICE}" && echo "${DB_SERVICE}: activo" || echo "${DB_SERVICE}: inactivo"
  systemctl status fail2ban --no-pager | sed -n '1,6p'
}

restart_service() {
  local svc="$1"
  log "Reiniciando ${svc}..."
  sudo systemctl restart "${svc}"
  sudo systemctl status "${svc}" --no-pager | sed -n '1,6p'
}

run_backup_now() {
  log "Lanzando backup inmediato..."
  /usr/local/bin/backup_servicios.sh
}

tail_login_logs() {
  /usr/local/bin/login_logs.sh --follow
}

show_disk() {
  df -hT /
  lsblk
}

deploy_git() {
  log "Actualizando app desde git..."
  cd "${APP_DIR}"
  git pull --rebase
  # ejecutar migraciones, instalar composer, permisos, etc.
  echo "Despliegue finalizado"
}

usage() {
  cat <<EOF
operator.sh - Acciones:
  1) check         - chequear servicios
  2) restart srv   - reiniciar servicio (ej: restart httpd)
  3) backup now    - forzar backup
  4) logs login    - ver logs de intentos de login
  5) disk          - ver disco
  6) deploy        - actualizar desde git
EOF
}

case "${1:-}" in
  check) check_services ;;
  restart) restart_service "${2:-$SERVICE_NAME}" ;;
  backup) run_backup_now ;;
  logs) tail_login_logs ;;
  disk) show_disk ;;
  deploy) deploy_git ;;
  *) usage ;;
esac
