FROM php:8.2-apache

# Install PostgreSQL extension
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Enable Apache rewrite module (opsional tapi bagus untuk routing)
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Railway akan otomatis memetakan port 80
EXPOSE 80