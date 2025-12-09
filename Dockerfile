# --- Dockerfile Sencillo para Laravel en Render (Prueba) ---
# Archivo: Dockerfile
# Base: PHP 8.3 FPM con Alpine Linux (para ser ligero)

FROM php:8.3-fpm-alpine

# Instala dependencias del sistema y extensiones de PHP.
# NOTA: Eliminamos NGINX y Supervisor, pero mantenemos Node.js y PostgreSQL.
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
# Ejecutamos todos los comandos de optimización en la fase de construcción.
# Esto asegura que la imagen final sea un artefacto optimizado.

# 1. Instala dependencias de Composer (solo producción)
RUN composer install --prefer-dist --no-dev --optimize-autoloader

# 2. Instala dependencias de Node.js
RUN npm install

# 3. Compila los assets de frontend para producción (Vite/Tailwind)
RUN npm run build

# 4. Optimización de Laravel (Genera archivos de cache de configuración y rutas)
# Usamos 'sh -c' para permitir múltiples comandos encadenados
RUN sh -c "php artisan config:cache && php artisan route:cache && php artisan view:cache"

# Otorga permisos de escritura al directorio 'storage'
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# Expone el puerto por defecto
EXPOSE 8000

# --- COMANDO DE INICIO (RUNTIME) ---
# CMD: Comando final que se ejecuta al iniciar el contenedor.
# Usamos 'php artisan migrate --force' ANTES de iniciar el servidor.
# Luego, iniciamos el servidor web incorporado de Laravel en el puerto 8000.
# NOTA: Debes configurar el Start Command en Render con el mismo valor (o dejarlo vacío
# y Render usará este CMD como comando de inicio).

CMD sh -c "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"