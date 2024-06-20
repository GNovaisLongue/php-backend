# composer
FROM composer:2.5 as backend_builder

WORKDIR /app/backend/
COPY composer.json composer.lock /app/backend/
RUN composer install --no-dev --optimize-autoloader

# apache
FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql

WORKDIR /var/www/html

COPY --from=backend_builder /app/backend/ /var/www/html
COPY ./index.php /var/www/html
COPY ./App/Db /var/www/html/App/Db
COPY ./App/Model /var/www/html/App/Model

CMD ["apache2-foreground"]

# # To rewrite module, allowing URL rewriting and manipulation.
# RUN a2enmod rewrite
# # Replace the default Apache configuration with a custom configuration
# COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf
# Start Apache