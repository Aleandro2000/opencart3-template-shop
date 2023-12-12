# Use an official PHP runtime as a parent image
FROM php:7.4-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install necessary dependencies
RUN apt-get update && \
    apt-get install -y unzip libzip-dev && \
    docker-php-ext-install zip pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Copy your customized OpenCart files into the container
COPY ./ /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

# Expose ports
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]