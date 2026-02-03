FROM php:8.2-apache

# Install PostgreSQL extension
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Hapus SEMUA konfigurasi mpm yang ada secara paksa untuk menghindari konflik
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf

# Aktifkan hanya mpm_prefork
RUN a2enmod mpm_prefork

RUN a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80