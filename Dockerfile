FROM php:8.2-apache

# Установка MySQL клиента и расширений
RUN apt-get update && \
    apt-get install -y default-mysql-client libzip-dev && \
    docker-php-ext-install pdo_mysql zip && \
    a2enmod rewrite

# Копирование файлов
COPY . /var/www/html/

# Фикс прав
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]