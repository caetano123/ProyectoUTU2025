#!/bin/bash

# Ruta al archivo .env
ENV_FILE="$(dirname "$0")/../../.env"

# Cargar las variables del archivo .env
if [ -f "$ENV_FILE" ]; then
  export $(grep -v '^#' "$ENV_FILE" | xargs)
else
  echo "❌ Archivo .env no encontrado en $ENV_FILE"
  exit 1
fi

# Ruta base relativa desde el script
BASE_DIR="$(dirname "$0")/../../database"

# Mostrar configuración
echo "📌 Conectando a la base de datos con:"
echo "   Host: $DB_HOST"
echo "   Usuario: $DB_USERNAME"
echo "   Base de datos: $DB_DATABASE"

# Crear la base de datos si no existe
echo "📁 Verificando si la base de datos '$DB_DATABASE' existe..."
mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" -h"$DB_HOST" -e "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Ejecutar todos los archivos .sql en /database/migrations/
echo "🔄 Ejecutando migraciones desde $BASE_DIR/migrations"

for file in "$BASE_DIR/migrations"/*.sql; do
  echo "➡ Ejecutando $file"
  mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" -h"$DB_HOST" "$DB_DATABASE" < "$file"
done

echo "✅ Migraciones completadas."

