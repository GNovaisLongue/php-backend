# Stage 1: Install Composer dependencies
FROM composer:2 AS vendor_builder
WORKDIR /App

RUN --mount=type=cache,target=/tmp/cache
COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-dist

# Stage 2: Build the final application image
FROM php:8.2-apache
WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql
# Copy your custom Apache config
COPY default.conf /etc/apache2/sites-available/000-default.conf
# Enable Apache rewrite module
RUN a2enmod rewrite
COPY --from=vendor_builder /App/ /var/www/html/

CMD ["apache2-foreground"]