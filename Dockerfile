FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=node:20 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20 /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node:20 /usr/local/bin/npm /usr/local/bin/npm

WORKDIR /var/www
COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && npm run build
RUN cp .env.example .env && php artisan key:generate
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY nginx.conf /etc/nginx/sites-available/default

EXPOSE 80
CMD bash -c "php-fpm -D && nginx -g 'daemon off;'"