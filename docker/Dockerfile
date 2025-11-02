FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y nano && \
    docker-php-ext-install pdo pdo_mysql && \
    a2enmod rewrite

# Copiar el código fuente
COPY . /var/www/html

# Definir directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer dentro del contenedor
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install || true

# Copiar y activar VirtualHost personalizado
COPY ./docker/vhost.conf /etc/apache2/sites-available/servicios.conf
RUN a2dissite 000-default.conf && a2ensite servicios.conf

# Dar permisos completos al contenido
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Exponer el puerto HTTP
EXPOSE 80

CMD ["apache2-foreground"]
