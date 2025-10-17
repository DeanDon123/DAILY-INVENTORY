# Use the official PHP Apache image
FROM php:8.2-apache

# Copy all your project files into the container
COPY . /var/www/html

# Set chicken_inventory.php as the default page
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-enabled/directory-index.conf

# Expose port 80
EXPOSE 80

# Enable PHP mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
