FROM php:8.3-cli
RUN apt-get update && apt-get install -y git unzip libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN cp -n .env.example .env && sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env \
    && sed -i '/^DB_DATABASE=/d;/^DB_HOST=/d;/^DB_PORT=/d;/^DB_USERNAME=/d;/^DB_PASSWORD=/d' .env \
    && touch database/database.sqlite && php artisan key:generate
EXPOSE 8000
CMD php artisan migrate --force --seed && php artisan serve --host=0.0.0.0 --port=8000
