FROM php:8.0-apache
LABEL description="Image de développement Php-Apache-Composer"
LABEL version="1.0"

# Updating container
WORKDIR /root
RUN apt update && apt upgrade -y
RUN apt install -y software-properties-common git zip zsh wget gnupg
RUN apt clean && apt autoremove

RUN a2enmod rewrite

# Installing php modules
RUN sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
RUN wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -
RUN apt-get update
RUN apt-get -y install postgresql libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Restoring Apache env
WORKDIR /var/www/html
EXPOSE 80/tcp