FROM quay.io/centos/centos:stream9

# Instalación básica
RUN dnf -y update && \
    dnf -y install dnf-utils curl wget unzip nano && \
    dnf -y install httpd && \
    dnf -y install php php-mysqlnd php-pdo php-mbstring php-xml php-json php-cli php-common && \
    dnf -y install mariadb-server && \
    dnf clean all

# Configurar Apache
COPY ./apache/vhost.conf /etc/httpd/conf.d/vhost.conf

RUN sed -i '/<Directory "\/var\/www\/html">/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/httpd/conf/httpd.conf

# Script de instalación
COPY setup.sh /setup.sh
RUN chmod +x /setup.sh

# Exponer puertos
EXPOSE 80 3306

# Ejecutar
CMD ["/setup.sh"]

