FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    vim \
    unzip \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy your custom Apache config
COPY default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/

# Copy the project files
COPY composer.json composer.json
COPY .env .env
COPY ./App /var/www/html/App
COPY ./index.php .

# Install dependencies
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader

CMD ["apache2-foreground"]
