FROM php:8.4-apache

# Instalar la extensión mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Activar mod_rewrite de Apache (opcional, pero útil si usás rutas amigables o .htaccess)
RUN a2enmod rewrite

# (Opcional) Copiar virtualhost personalizado si usás uno
COPY ../apache/vhost.conf /etc/apache2/sites-available/000-default.conf

