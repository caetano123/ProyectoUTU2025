#!/bin/bash

# Variables fijas definidas antes de ejecutar el script, por ejemplo:
 DB_HOST="localhost"
 DB_USERNAME="root"
 DB_PASSWORD=""
 DB_DATABASE="ServiciOs"

# Comprobamos que las variables estén definidas
if [ -z "$DB_HOST" ] || [ -z "$DB_USERNAME" ] || [ -z "$DB_PASSWORD" ] || [ -z "$DB_DATABASE" ]; then
  echo "❌ Error: Variables DB_HOST, DB_USERNAME, DB_PASSWORD y DB_DATABASE deben estar definidas antes de ejecutar este script."
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
