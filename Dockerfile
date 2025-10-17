# Use the official PHP Apache image
FROM php:8.2-apache

# Copy all your project files into the container
COPY . /var/www/html

# Expose port 80 (default web server port)
EXPOSE 80

# Enable PHP extensions if needed (optional)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
