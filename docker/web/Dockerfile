FROM php:8.2-apache

# Install common PHP extensions you may need; adjust as your app requires
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libpng-dev libjpeg-dev libwebp-dev libzip-dev libicu-dev \
       unzip git \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd mysqli pdo pdo_mysql intl zip opcache \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers expires

# Use repository root as document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# Apply our vhost config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's!#ServerName.*!ServerName localhost!g' /etc/apache2/sites-available/000-default.conf

# Optional: small production-leaning PHP tweaks (feel free to adjust/remove)
RUN { \
      echo "memory_limit=512M"; \
      echo "upload_max_filesize=32M"; \
      echo "post_max_size=32M"; \
      echo "max_execution_time=60"; \
      echo "opcache.enable=1"; \
      echo "opcache.memory_consumption=128"; \
      echo "opcache.max_accelerated_files=10000"; \
      echo "opcache.validate_timestamps=1"; \
      echo "opcache.revalidate_freq=2"; \
    } > /usr/local/etc/php/conf.d/keydera.ini

# Final entrypoint
CMD ["apache2-foreground"]
