# --- Dockerfile Sencillo para Laravel en Render ---
# Base: PHP 8.3 FPM con Alpine Linux (para ser ligero)

FROM php:8.3-fpm-alpine

# Instala dependencias del sistema y extensiones de PHP.
RUN apk update && apk add --no-cache \
    git \
    openssl \
    curl \
    postgresql-dev \
    nodejs \
    npm \
    make \
    g++ \
    \
    # Instala las extensiones de PHP necesarias.
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install exif \
    \
    # Limpia la caché.
    && rm -rf /var/cache/apk/*

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo (ROOT de la aplicación)
WORKDIR /var/www

# Copia los archivos del proyecto
COPY . .

# --- FASE DE CONSTRUCCIÓN/OPTIMIZACIÓN (BUILD STAGE) ---

# 1. Instala dependencias de Composer
RUN composer install --prefer-dist --no-dev --optimize-autoloader

# 2. Instala dependencias de Node.js y compila assets
RUN npm install
RUN npm run build

# --- GENERACIÓN DE CLAVE Y OPTIMIZACIÓN DE LARAVEL ---

# 3. Generar Clave (Debe ir antes de config:cache)
RUN php artisan key:generate

# 4. Optimización de Configuración (Configura el caché basándose en la nueva clave)
RUN php artisan config:cache

# 5. Optimización de Rutas
RUN php artisan route:cache

# 6. Optimización de Vistas
RUN php artisan view:cache

# Otorga permisos de escritura al directorio 'storage'
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# Expone el puerto por defecto (8000 para el servidor PHP integrado)
EXPOSE 8000

# Comando final (CMD): Limpia caché (por seguridad), migra, seedea e inicia el servidor.
CMD sh -c "php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8000"