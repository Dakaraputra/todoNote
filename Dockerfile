FROM php:8.2-apache

# Install PostgreSQL extension
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Pastikan hanya satu MPM aktif (prefork)
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Enable Apache rewrite module (opsional untuk routing)
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

EXPOSE 80
