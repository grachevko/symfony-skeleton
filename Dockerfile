FROM php:fpm-alpine

MAINTAINER Konstantin Grachev <me@grachevko.ru>

ENV APP_DIR=/usr/local/app \
  COMPOSER_BIN_DIR=/usr/local/bin \
  COMPOSER_CACHE_DIR=/var/cache/composer \
  COMPOSER_ALLOW_SUPERUSER=1

ENV PATH=${APP_DIR}/bin:${PATH}

WORKDIR ${APP_DIR}

RUN set -ex \
    && apk add --no-cache \
        git \
        icu-dev \
        zlib-dev \
    && docker-php-ext-install zip intl pdo_mysql iconv opcache \
    && rm -rf /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && pecl install xdebug && apk del .build-deps

ARG SOURCE_DIR=.

COPY $SOURCE_DIR/composer.* ${APP_DIR}/
RUN if [ -f composer.lock ]; then \
    composer install --no-scripts --no-interaction --no-autoloader --no-progress --prefer-dist \
    && rm -rf ${COMPOSER_CACHE_DIR}/* ; fi

COPY $SOURCE_DIR/ ${APP_DIR}/

RUN set -ex \
    && { \
        echo 'opcache.enable = 1'; \
        echo 'opcache.enable_cli = 1'; \
        echo 'opcache.memory_consumption = 64'; \
        echo 'opcache.interned_strings_buffer = 4'; \
        echo 'opcache.max_accelerated_files = 15000'; \
        echo 'opcache.max_wasted_percentage = 10'; \
        echo ';opcache.use_cwd = 1'; \
        echo 'opcache.validate_timestamps = 0'; \
        echo ';opcache.revalidate_freq = 2'; \
        echo ';opcache.revalidate_path = 0'; \
        echo 'opcache.save_comments = 1'; \
        echo 'opcache.load_comments = 1'; \
    } > $PHP_INI_DIR/conf.d/opcache.ini \
    && { \
        echo 'xdebug.remote_enable=On'; \
        echo 'xdebug.remote_autostart=On'; \
        echo 'xdebug.remote_connect_back=On'; \
    } >> ${PHP_INI_DIR}/conf.d/xdebug.ini \
    && { \
        echo 'date.timezone = UTC'; \
        echo 'short_open_tag = off'; \
    } > ${PHP_INI_DIR}/php.ini

VOLUME ${APP_DIR}/var/logs
VOLUME ${APP_DIR}/var/sessions

ENTRYPOINT ["docker-entrypoint.sh"]
CMD []
