FROM php:8.2-fpm

# Install small utilities and the PDO MySQL extension required by the app
RUN apt-get update && apt-get install -y \
    git unzip curl \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally so vendor dependencies can be installed
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock* ./

# nainstaluj závislosti
RUN composer install --no-dev --optimize-autoloader --no-interaction

# zkopíruj zbytek aplikace
COPY . .

WORKDIR /var/www