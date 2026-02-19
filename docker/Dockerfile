FROM ghcr.io/railwayapp/frankenphp:8.2

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy app code
COPY . .

RUN chown -R www-data:www-data /app

# Expose the port (Railway uses $PORT)
EXPOSE 8080

# Start FrankenPHP
CMD ["frankenphp", "-S", "0.0.0.0:${PORT}", "-t", "public"]