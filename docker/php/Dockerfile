# Seven Hills Digital — PHP-FPM 8.4 image for Laravel 12 + Filament
# Mirrors the Excellent Real Estate image so both stacks stay in step.
FROM php:8.4-fpm-alpine

# System deps for common PHP extensions + composer + node (for building assets).
# gd/exif are here for the demo-tenant screenshots and Open Graph imagery.
RUN apk add --no-cache \
        bash \
        git \
        curl \
        icu-dev \
        libzip-dev \
        zip \
        unzip \
        oniguruma-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        tzdata \
        nodejs \
        npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        bcmath \
        intl \
        zip \
        gd \
        exif \
        pcntl \
    && rm -rf /var/cache/apk/*

# Timezone: Africa/Johannesburg
ENV TZ=Africa/Johannesburg
RUN cp /usr/share/zoneinfo/Africa/Johannesburg /etc/localtime && echo "Africa/Johannesburg" > /etc/timezone

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Run as a non-root user matching the host UID to avoid volume permission pain
RUN addgroup -g 1000 -S www && adduser -u 1000 -S www -G www
USER www

EXPOSE 9000
CMD ["php-fpm"]
