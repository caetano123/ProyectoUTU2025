#!/bin/bash
DB_USERNAME="root"
DB_PASSWORD="cae2007"
SERVICIOS_DB_DATABASE="ServiciOs"
SERVICIOS_BACKUP_DIR="/opt/ProyectoUTU2025/backups"
SERVICIOS_LOG_DIR="/opt/ProyectoUTU2025/logs"

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="${SERVICIOS_BACKUP_DIR}/servicios_backup_${TIMESTAMP}.tar.gz"
DB_BACKUP_FILE="${SERVICIOS_BACKUP_DIR}/db_backup_${TIMESTAMP}.sql"

# Backup de base de datos
mysqldump -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${SERVICIOS_DB_DATABASE}" > "${DB_BACKUP_FILE}"

# Backup de archivos
tar -czf "${BACKUP_FILE}" -C / \
    var/www/html/servicios \
    opt/servicios/config \
    opt/servicios/uploads

# Limpiar backups antiguos (más de 7 días)
find "${SERVICIOS_BACKUP_DIR}" -name "*.tar.gz" -mtime +7 -delete
find "${SERVICIOS_BACKUP_DIR}" -name "*.sql" -mtime +7 -delete

echo "$(date): Backup completado - ${BACKUP_FILE}" >> "${SERVICIOS_LOG_DIR}/backup.log"
