FROM php:8.2-fpm

# Install small utilities and the PDO MySQL extension required by the app
RUN apt-get update && apt-get install -y \
    git unzip curl \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

COPY composer.json composer.lock ./

# Install composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

COPY . .

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Fix permissions
RUN chown -R www-data:www-data /var/www

# Expose Railway port
EXPOSE 8080

# Start php-fpm and nginx
CMD php-fpm & nginx -g "daemon off;"