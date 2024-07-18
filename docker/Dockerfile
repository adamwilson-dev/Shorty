# Use PHP 8.3 with Apache as base image
FROM php:8.3-apache

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod ssl

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    openssl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Apache configuration file
COPY apache-ssl-config.conf /etc/apache2/sites-available/000-default.conf

# Generate SSL certificate
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=www.example.com"

# Install dependencies
# @todo - re-enable once composer has been added
#RUN composer install

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html

# Expose ports 80 and 443
EXPOSE 80
EXPOSE 443

CMD ["apache2-foreground"]
