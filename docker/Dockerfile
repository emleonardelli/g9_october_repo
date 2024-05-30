FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    tzdata \
    libzip-dev \
    zip \
    libpq-dev \
    unzip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip soap dom

ENV TZ America/Argentina/Buenos_Aires

#COPY start.sh /usr/local/bin/start

# Arguments defined in docker-compose.yml
ARG uid

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$uid $uid

RUN mkdir -p /home/$uid/.composer && \
    chown -R $uid:$uid /home/$uid

WORKDIR /var/www

ENV APACHE_LOG_DIR /var/log/apache2

COPY apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
COPY apache/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN sed -i "s/80/9000/g" /etc/apache2/ports.conf

USER $uid

EXPOSE 9000

#CMD ["/usr/local/bin/start"]
