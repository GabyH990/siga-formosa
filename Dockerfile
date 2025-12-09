# --- Dockerfile Final y Optimizado para Render ---
# Archivo: Dockerfile
# Base: PHP 8.3 FPM con Alpine Linux (para ser ligero)

FROM php:8.3-fpm-alpine

# Instala dependencias del sistema y extensiones de PHP (PostgreSQL, Node.js).
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

# 1. Instala dependencias de Composer (separa RUN para mejor caché)
RUN composer install --prefer-dist --no-dev --optimize-autoloader

# 2. Instala dependencias de Node.js
RUN npm install

# 3. Compila assets de frontend
RUN npm run build

# --- SOLUCIÓN CRÍTICA: COPIA .env.example a .env ---
# Permite que los comandos de Artisan modifiquen el .env y usen variables de entorno.
RUN cp .env.example .env

# 4. Generar Clave (Debe ir antes de config:cache)
RUN php artisan key:generate

# 5. Optimización de Configuración
RUN php artisan config:cache

# 6. Optimización de Rutas
RUN php artisan route:cache

# 7. Optimización de Vistas
RUN php artisan view:cache

# Otorga permisos de escritura al directorio 'storage'
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# Expone el puerto por defecto (8000 para el servidor PHP integrado)
EXPOSE 8000

# --- COMANDO FINAL (RUNTIME) ---
# Se utiliza el servidor PHP incorporado.
# Orden CRÍTICO para evitar el error "relation cache does not exist":
# 1. Limpieza de cache de configuración (Seguro)
# 2. Migración (Crea las tablas 'cache' y 'sessions')
# 3. Limpieza de Cache/Rutas (Ahora que las tablas existen)
# 4. Seeding
# 5. Inicio del Servidor

CMD sh -c " \
    php artisan config:clear && \
    php artisan migrate --force && \
    php artisan cache:clear && php artisan route:clear && php artisan view:clear && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=8000 \
"