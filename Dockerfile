FROM php:8.2-apache

# Apache Rewrite aktivieren
RUN a2enmod rewrite

# Installiere cron für geplante Aufgaben
RUN apt-get update && apt-get install -y cron

WORKDIR /var/www/html

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

# Nicht zwingenderweise nötig
# Autoresize-Skript kopieren und ausführbar machen
COPY autoresize.sh /usr/local/bin/autoresize.sh
RUN chmod +x /usr/local/bin/autoresize.sh
# Cronjob-Datei kopieren und einrichten
COPY crontab.txt /etc/cron.d/geofsdisplay-cron
RUN chmod 0644 /etc/cron.d/geofsdisplay-cron
# Stelle sicher, dass Logs existieren
RUN touch /var/log/autoresize.log

# Startbefehl für Apache und Cron gleichzeitig
CMD ["sh", "-c", "service cron start && apache2-foreground"]