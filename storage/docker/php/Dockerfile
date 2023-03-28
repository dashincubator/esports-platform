FROM php:7.4.3-fpm-alpine

ARG PHP_DIR
ARG PHP_OPCACHE_REVALIDATE_FREQ=0
ARG PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
ARG PHP_SOCKET

ENV EXT_DECIMAL_VERSION=1.x-php8 \
    EXT_MAIL_VERSION=1.4.1 \
    EXT_REDIS_VERSION=5.3.2

# Use Default Production Configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Upload Configuration
COPY ./conf.d "$PHP_INI_DIR/conf.d"
COPY ./php-fpm.d /usr/local/etc/php-fpm.d

# Upload Helper Scripts
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Define Root Directory
WORKDIR $PHP_DIR

# Update Package List
RUN apk update \
    # Upgrade Existing Extensions
    && apk upgrade \
    # Install Base Dependencies
    && apk add --no-cache \
        curl \
        gettext \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        moreutils \
        zlib-dev \
    && docker-php-ext-install \
        bcmath \
        exif \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    # Install GD With Jpeg
    && docker-php-ext-configure gd -with-jpeg \
        && docker-php-ext-install -j$(nproc) gd \
    # Install PHP Dependencies
    && docker-php-source extract \
        # PEAR
        && pear channel-update pear.php.net \
        && pear install --alldeps \
                Mail-$EXT_MAIL_VERSION \
        # PECL+ Extensions
        && install-php-extensions \
            decimal \
            redis-$EXT_REDIS_VERSION \
        # Substitute Variables
        && sh -c " \
            envsubst '\$PHP_SOCKET' < /usr/local/etc/php-fpm.d/www.conf | sponge /usr/local/etc/php-fpm.d/www.conf \
            && envsubst '\$PHP_OPCACHE_REVALIDATE_FREQ \$PHP_OPCACHE_VALIDATE_TIMESTAMPS' < $PHP_INI_DIR/conf.d/opcache.ini | sponge $PHP_INI_DIR/conf.d/opcache.ini \
        " \
    # Cleanup ( Alpine Is Handled By '--no-cache' Arguments )
    && docker-php-source delete \
    && rm -fr /tmp/pear

# Run In Foreground
CMD ["php-fpm", "-F"]
