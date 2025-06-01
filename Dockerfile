FROM php:7.3-apache

# Installiere GD und Cron
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    cron \
    # Entferne die explizite --with-freetype und --with-jpeg Konfiguration
    # und installiere gd direkt. Dies funktioniert oft besser bei älteren Images.
    && docker-php-ext-install gd

# Apache Rewrite aktivieren
RUN a2enmod rewrite

# Apache root setzen & Rechte
WORKDIR /var/www/html
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Fehleranzeige im Container
RUN echo "display_errors=On\n" \
         "display_startup_errors=On\n" \
         "error_reporting=E_ALL\n" \
         "log_errors=On\n" \
         "error_log=/proc/self/fd/2" \
         > /usr/local/etc/php/conf.d/dev.ini

# Start Apache im Vordergrund
CMD ["apache2-foreground"]