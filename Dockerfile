# --- Dockerfile Todo-en-Uno para Render ---
FROM php:8.3-fpm-alpine

# 1. Instalar dependencias del sistema
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

# 3. Configurar directorio de trabajo
WORKDIR /var/www

# 4. Copiar archivos del proyecto
COPY . .

# 5. Instalar dependencias de PHP y Node
RUN composer install --prefer-dist --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# 6. Configuración inicial básica
# Copiamos .env para que key:generate funcione.
# IMPORTANTE: NO ejecutamos config:cache aquí para evitar congelar credenciales viejas.
RUN cp .env.example .env
RUN php artisan key:generate

# 7. Permisos de carpeta
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# 8. Exponer puerto
EXPOSE 8000

# -----------------------------------------------------------
# 9. CREACIÓN DEL SCRIPT DE INICIO (ENTRYPOINT)
# Escribimos el script directamente en el contenedor
# -----------------------------------------------------------
RUN printf "#!/bin/sh\n\
set -e\n\
\n\
echo '🚀 Iniciando contenedor en Render...'\n\
\n\
echo '🧹 Limpiando caché antigua para leer variables reales...'\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan route:clear\n\
php artisan view:clear\n\
\n\
echo '📦 Ejecutando migraciones...'\n\
php artisan migrate --force\n\
\n\
echo '🌱 Ejecutando seeders (idempotentes)...'\n\
php artisan db:seed --force\n\
\n\
echo '🔥 Arrancando servidor Laravel...'\n\
exec php artisan serve --host=0.0.0.0 --port=8000\n\
" > /usr/local/bin/start-container

# Hacemos el script ejecutable
RUN chmod +x /usr/local/bin/start-container

# 10. Definimos el comando de inicio
CMD ["/usr/local/bin/start-container"]