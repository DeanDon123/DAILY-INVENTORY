# Use the official PHP Apache image
FROM php:8.2-apache

# Copy all your project files into the container
COPY . /var/www/html

# Enable required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli && docker-php-ext-enable pdo_mysql mysqli

# Set index.php as the default
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-enabled/directory-index.conf

# Expose port 80
EXPOSE 80
