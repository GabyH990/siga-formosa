# --- Dockerfile ---
# Archivo: Dockerfile
# Ubicación: En la raíz de tu proyecto Laravel
# Base: PHP 8.3 FPM con Alpine Linux para ser ligera

FROM php:8.3-fpm-alpine

# Instala dependencias del sistema, NGINX, Supervisor, Node.js y paquetes de desarrollo.
RUN apk update && apk add --no-cache \
    nginx \
    git \
    supervisor \
    openssl \
    curl \
    postgresql-dev \
    nodejs \
    npm \
    make \
    g++ \
    \
    # Instala las extensiones de PHP necesarias. pdo_pgsql es CRÍTICO para PostgreSQL.
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

# Copia los archivos del proyecto al contenedor
COPY . .

# --- FASE DE CONSTRUCCIÓN/OPTIMIZACIÓN (BUILD STAGE) ---
# Aquí se instalan y compilan todos los assets para producción

# 1. Instala dependencias de Composer (solo las de producción)
RUN composer install --prefer-dist --no-dev --optimize-autoloader

# 2. Instala dependencias de Node.js (incluyendo Tailwind y Vite)
RUN npm install

# 3. Compila los assets de frontend para producción (CSS/JS). Usa el script 'build' de tu package.json.
# Esto crea la carpeta public/build.
RUN npm run build

# --- CONFIGURACIÓN DEL ENTORNO DE EJECUCIÓN ---

# Otorga permisos de escritura al directorio 'storage'
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# Copia la configuración de NGINX, Supervisor y el script de entrada (Requiere la carpeta 'docker/')
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Asegúrate de que el script de entrada sea ejecutable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expone el puerto por defecto de NGINX
EXPOSE 80

# Comando final: ejecuta el script que optimiza, migra e inicia los servicios
CMD ["entrypoint.sh"]