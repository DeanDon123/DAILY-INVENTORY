# Use the official PHP Apache image
FROM php:8.2-apache

# Copy all your project files into the container
COPY . /var/www/html

# Set permissions for the web root (optional but recommended)
RUN chown -R www-data:www-data /var/www/html

# Set index.php as the default page
RUN echo "DirectoryIndex index.php" > /etc/apache2/conf-enabled/directory-index.conf

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (useful for frameworks or clean URLs)
RUN a2enmod rewrite

# Expose port 80 to the outside world
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
