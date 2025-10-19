# ProyectoUTU

Este proyecto es una aplicación PHP con base de datos MariaDB, configurada para correr en Docker usando docker-compose.

## Requisitos

- Docker y Docker Compose instalados en su máquina.
- Git instalado para clonar el repositorio.

## Pasos para poner el proyecto en funcionamiento

### 1. Clonar el repositorio

```bash
git clone https://github.com/caetano123/ProyectoUTU2025.git
cd ProyectoUTU
2. Configurar las variables de entorno
En la raíz del proyecto hay un archivo .env con las credenciales de la base de datos.

3. Levantar los contenedores Docker

docker-compose up -d --build

Esto va a:

Crear y arrancar el contenedor de MariaDB con la base de datos inicializada.

Crear y arrancar el contenedor con Apache y PHP 8.2 con la extensión mysqli.

Crear un contenedor para la consola CentOS.

4. Instalar dependencias PHP (vendor)
Desde la raíz del proyecto (donde está el archivo composer.json) ejecutá:

docker exec -it servidor_web bash
composer install
exit
Esto instala las librerías necesarias (como vlucas/phpdotenv).

5. Configurar el archivo hosts (opcional)
Para poder acceder al proyecto desde http://proyecto.local:8080 agregar esta línea en el archivo /etc/hosts de su sistema:

127.0.0.1   proyecto.local


6. Acceder al proyecto
Abrir el navegador y entrar a:

http://proyecto.local:8080
O si no configuraron el hosts, pueden usar:

http://localhost:8080


7. Base de datos y scripts de inicialización

La base de datos se inicializa automáticamente con el script SQL que está en db-init-scripts/init.sql.
Si quieren resetear la base de datos, borrar el volumen mariadb_data con:

docker volume rm proyecto_mariadb_data
Y luego levantar los contenedores de nuevo.

Notas importantes
No subir la carpeta vendor/ al repositorio, porque se genera con composer install.

Si agregan nuevas dependencias PHP, correr composer install o composer update dentro del contenedor servidor_web.

El código fuente principal está en app/public/ y está montado en el contenedor Apache en /var/www/html.

Ramas de Git
main: versión estable y lista para producción.

dev: desarrollo activo.

feature/xxx: nuevas funcionalidades en desarrollo.
