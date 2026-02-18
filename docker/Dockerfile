FROM php:8.2-fpm

# Install small utilities and the PDO MySQL extension required by the app
RUN apt-get update && apt-get install -y \
    git unzip curl \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer globally so vendor dependencies can be installed
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock* ./

# Install dependencies without dev dependencies, optimize autoloader and avoid interaction
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the rest of the application code to the container
COPY . .

WORKDIR /var/www