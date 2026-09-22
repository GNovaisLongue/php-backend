# Single-stage build: no runtime third-party deps, only composer autoloader.
FROM php:8.2-apache
WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql

COPY default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy app source (composer.json for autoload metadata + code).
COPY composer.json ./
COPY index.php ./
COPY App/ ./App/
COPY .htaccess ./

# Generate optimized autoloader in-image (no network deps to fetch).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --no-dev --no-interaction

CMD ["apache2-foreground"]
