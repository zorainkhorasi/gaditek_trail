FROM php:8.1-apache

WORKDIR /var/www/html

# Add additional Dockerfile instructions below as needed


RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY . .

EXPOSE 4000

# CMD ["apache2-foreground"]

CMD ["php", "-S", "0.0.0.0:4000", "-t", "public"]


