FROM php:fpm-alpine

MAINTAINER Konstantin Grachev <ko@grachev.io>

ENV APP_DIR /usr/local/app
ENV PATH ${APP_DIR}/bin:${PATH}

WORKDIR ${APP_DIR}

RUN set -ex \
    && apk --update add \
        icu-dev \
        git \
        openssh-client \
        tzdata \
        zlib-dev \
    && docker-php-ext-install zip intl pdo_mysql iconv mbstring opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -L -o /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar \
    && chmod +x /usr/local/bin/phpunit

RUN set -ex \
    && cp /usr/src/php/php.ini-production $PHP_INI_DIR/php.ini \
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
        echo 'date.timezone = Europe/Moscow'; \
    } > $PHP_INI_DIR/conf.d/date.ini

COPY ./ ${APP_DIR}

RUN chmod +x -R $APP_DIR/bin/*

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
