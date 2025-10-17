# Use official PHP with Apache
FROM php:8.2-apache

# Copy project files into Apache web root
COPY . /var/www/html/

# Expose port 80 to the internet
EXPOSE 80
add Dockerfile for deployment
