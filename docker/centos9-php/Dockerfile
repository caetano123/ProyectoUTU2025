FROM quay.io/centos/centos:stream9

# 1. Instalar herramientas básicas
RUN dnf -y update && \
    dnf -y install \
    wget \
    nano \
    git \
    unzip \
    tar \
    openssl \
    mysql \
    && dnf clean all

# 2. Instalar Apache, PHP y extensiones + PHP-FPM
RUN dnf -y install \
    httpd \
    php \
    php-common \
    php-fpm \
    php-opcache \
    php-cli \
    php-gd \
    php-curl \
    php-mysqlnd \
    php-mbstring \
    php-xml \
    php-zip \
    php-json \
    php-intl \
    php-pecl-apcu \
    && dnf clean all

# 3. Configurar servicios
RUN echo "ServerName localhost" >> /etc/httpd/conf/httpd.conf && \
    mkdir -p /var/www/html && \
    chown -R apache:apache /var/www && \
    chmod -R 755 /var/www && \
    sed -i 's|listen = /run/php-fpm/www.sock|listen = 127.0.0.1:9000|' /etc/php-fpm.d/www.conf && \
    sed -i 's|^;listen.allowed_clients = 127.0.0.1|listen.allowed_clients = 127.0.0.1|' /etc/php-fpm.d/www.conf

# 4. Instalar Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# 5. Copiar archivos de configuración
COPY ./docker/centos9-php/php.ini /etc/php.ini
COPY ./docker/vhost.conf /etc/httpd/conf.d/vhost.conf

# 6. Script de arranque
COPY ./docker/start.sh /start.sh
RUN chmod +x /start.sh

# 7. Exponer puerto
EXPOSE 80

# 8. Healthcheck
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# 9. Comando de inicio
CMD ["/start.sh"]
