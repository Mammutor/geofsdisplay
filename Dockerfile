FROM php:8.2-apache

# Apache Rewrite aktivieren
RUN a2enmod rewrite

# Installiere cron für geplante Aufgaben
RUN apt-get update && apt-get install -y cron

WORKDIR /var/www/html

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

# Startbefehl für Apache und Cron gleichzeitig
CMD ["sh", "-c", "apache2-foreground"]