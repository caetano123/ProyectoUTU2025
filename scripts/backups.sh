#!/bin/bash

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="/opt/ProyectoUTU2025/backups/servicios_backup_$TIMESTAMP.tar.gz"
DB_BACKUP_FILE="/opt/ProyectoUTU2025/backups/db_backup_$TIMESTAMP.sql"

# Crear directorios si no existen
mkdir -p "/opt/ProyectoUTU2025/backups"
mkdir -p "/opt/ProyectoUTU2025/logs"

# Backup de base de datos
mysqldump -u "root" -p"caetano2007" "ServiciOs" > "$DB_BACKUP_FILE"

# Backup de archivos
tar -czf "$BACKUP_FILE" -C / \
    opt/ProyectoUTU2025/app \
    opt/ProyectoUTU2025/config \

# Limpiar backups antiguos (más de 7 días)
find "/opt/ProyectoUTU2025/backups" -name "*.tar.gz" -mtime +7 -delete
find "/opt/ProyectoUTU2025/backups" -name "*.sql" -mtime +7 -delete

echo "$(date): Backup completado - $BACKUP_FILE" >> "/opt/ProyectoUTU2025/logs/backup.log"
