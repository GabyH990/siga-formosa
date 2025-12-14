# --- Dockerfile Definitivo para Render ---
FROM php:8.3-fpm-alpine

# 1. Instalar dependencias
RUN apk update && apk add --no-cache \
    git \
    openssl \
    curl \
    postgresql-dev \
    nodejs \
    npm \
    make \
    g++ \
    && docker-php-ext-install pdo_pgsql opcache pcntl exif \
    && rm -rf /var/cache/apk/*

# 2. Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3. Directorio de trabajo
WORKDIR /var/www

# 4. Copiar archivos
COPY . .

# 5. Instalar dependencias
RUN composer install --prefer-dist --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# 6. Configuración inicial (.env y key)
RUN cp .env.example .env
RUN php artisan key:generate

# 7. Permisos
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# 8. Exponer puerto
EXPOSE 8000

# -----------------------------------------------------------
# 9. SCRIPT DE INICIO (Con Cache Strategy)
# -----------------------------------------------------------
RUN printf "#!/bin/sh\n\
set -e\n\
\n\
echo '🚀 Iniciando contenedor...'\n\
\n\
echo '🧹 Limpiando caché para migraciones...'\n\
php artisan config:clear\n\
\n\
php artisan storage:link || true\n\
\n\
echo '📦 Ejecutando migraciones...'\n\
php artisan migrate --force\n\
\n\
echo '🌱 Ejecutando seeders...'\n\
php artisan db:seed --force\n\
\n\
echo '📝 Generando caché de configuración para el servidor...'\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo '🔥 Arrancando servidor...'\n\
exec php artisan serve --host=0.0.0.0 --port=8000\n\
" > /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

# 10. Comando de inicio
CMD ["/usr/local/bin/start-container"]
