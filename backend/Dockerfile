# ── Etapa de build ───────────────────────────────────────────
FROM php:8.3-fpm-alpine AS build

# 1. Instala dependencias de sistema y Node.js/npm para Vite
RUN apk add --no-cache \
      bash \
      curl \
      git \
      nodejs \
      npm \
      oniguruma-dev \
      icu-dev \
      build-base

# 2. Instala Composer
RUN curl -sS https://getcomposer.org/installer | php \
 && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

# 3. Copia TODO el proyecto (incluye artisan, rutas, recursos, etc.)
COPY . .

# 4. Instala dependencias PHP
#    Ahora composer puede encontrar artisan y ejecutar package:discover
RUN composer install --no-dev --optimize-autoloader

# 5. Instala dependencias JS y compila assets
RUN npm install \
 && npm run build

# ── Etapa de runtime ─────────────────────────────────────────
FROM php:8.3-fpm-alpine

# Extensiones PHP
RUN apk add --no-cache oniguruma-dev icu-dev \
 && docker-php-ext-install mbstring intl pdo_mysql

WORKDIR /var/www/html

# Copia desde la etapa build
COPY --from=build /var/www/html /var/www/html

EXPOSE 8000

# Al arrancar, corre migraciones y el servidor
CMD ["sh","-c","php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
