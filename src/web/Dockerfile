FROM php:8.0-apache

# Instalar extensiones MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite para Apache (es opcional pero de todos modos lo voy a colocar por si a las moscas)
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Asegurar que esten bien los permisos para la carpeta de reportes compartida
RUN mkdir -p /var/www/html/reports && chown -R www-data:www-data /var/www/html/reports
