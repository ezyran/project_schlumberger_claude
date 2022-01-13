FROM php:8.0-apache
LABEL description="Serveur de développement Php"
LABEL version="1.0"

# Updating container
WORKDIR /root
RUN apt update && apt upgrade -y
RUN apt clean && apt autoremove

# Configuring Apache env
RUN a2enmod rewrite

# Restoring Apache env
WORKDIR /var/www/html
EXPOSE 80/tcp