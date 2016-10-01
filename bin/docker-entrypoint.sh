#!/bin/sh

set -e

APP_DIR=${APP_DIR:=/usr/local/app}
SYMFONY_ENV=${SYMFONY_ENV:=dev}
NGINX_WEB_DIR=${NGINX_WEB_DIR:=/var/www}
PHP_INI_DIR=${PHP_INI_DIR:=/usr/local/etc/php}

OPCACHE=
BUILD_PARAMS=
COMPOSER=
REQUIREMENTS=
MIGRATION=
FIXTURES=
XDEBUG=
COMMAND=

for i in "$@"
do
case ${i} in
    --no-build-params)
    BUILD_PARAMS=false
    ;;
    --no-composer)
    COMPOSER=false
    ;;
    -m|--migrations)
    MIGRATION=true
    ;;
    -f|--fixtures)
    FIXTURES=true
    ;;
    --no-fixtures)
    FIXTURES=false
    ;;
    -x|--xdebug)
    XDEBUG=true
    ;;
    --no-xdebug)
    XDEBUG=false
    ;;
    *)
    # unknown option
    COMMAND=${COMMAND}' '${i}
    ;;
esac
    # past argument=value
    shift
done

{
    echo 'date.timezone = UTC';
    echo 'short_open_tag = off';
} > ${PHP_INI_DIR}/php.ini

if [ "$SYMFONY_ENV" == "dev" ]; then
    XDEBUG=${XDEBUG:=true}
    BUILD_PARAMS=${BUILD_PARAMS:=true}
    COMPOSER=${COMPOSER:="composer install --no-interaction --optimize-autoloader --prefer-source"}

    COMMAND=${COMMAND:='bin/console server:run 0.0.0.0:80'}
fi

if [ "$SYMFONY_ENV" == "test" ]; then
    export SYMFONY_DEBUG=0

    OPCACHE=${OPCACHE:=true}
    BUILD_PARAMS=${BUILD_PARAMS:=true}
    COMPOSER=${COMPOSER:="composer install --no-interaction --optimize-autoloader --no-progress --prefer-dist"}
    REQUIREMENTS=${REQUIREMENTS:=true}
    MIGRATION=${MIGRATION:=true}
    FIXTURES=${FIXTURES:=true}

    COMMAND=${COMMAND:="bin/console doctrine:schema:validate && phpunit"}
fi

if [ "$SYMFONY_ENV" == "prod" ]; then
    OPCACHE=${OPCACHE:=true}
    COMPOSER=${COMPOSER:="composer install --no-dev --no-interaction --optimize-autoloader --no-progress --prefer-dist"}
    MIGRATION=${MIGRATION:=true}

    COMMAND=${COMMAND:=php-fpm}
fi

if [ "$OPCACHE" == "true" ]; then
#    docker-php-ext-enable opcache # wait for fix "nm not found"
    echo "zend_extension=opcache.so" > ${PHP_INI_DIR}/conf.d/docker-php-ext-opcache.ini

    {
        echo 'opcache.enable = 1';
        echo 'opcache.enable_cli = 1';
        echo 'opcache.memory_consumption = 64';
        echo 'opcache.interned_strings_buffer = 4';
        echo 'opcache.max_accelerated_files = 15000';
        echo 'opcache.max_wasted_percentage = 10';
        echo ';opcache.use_cwd = 1';
        echo 'opcache.validate_timestamps = 0';
        echo ';opcache.revalidate_freq = 2';
        echo ';opcache.revalidate_path = 0';
        echo 'opcache.save_comments = 1';
        echo 'opcache.load_comments = 1';
    } > ${PHP_INI_DIR}/conf.d/opcache.ini

    echo -e '\n > opcache enabled\n'
fi

if [ "$BUILD_PARAMS" == "true" ]; then
    composer run-script build-parameters --no-interaction
fi

if [ "$COMPOSER" != "false" ]; then
    ${COMPOSER}
fi

if [ "$REQUIREMENTS" == "true" ]; then
    bin/symfony_requirements
fi

if [ "$MIGRATION" == "true" ]; then
    console doctrine:migrations:migrate --no-interaction --allow-no-migration
fi

if [ "$FIXTURES" == "true" ]; then
    bin/console doctrine:fixtures:load --no-interaction
fi

if [ "$XDEBUG" == "true" ]; then
#    docker-php-ext-enable xdebug # wait for fix "nm not found"
    echo "zend_extension=xdebug.so" > ${PHP_INI_DIR}/conf.d/docker-php-ext-xdebug.ini

    {
        echo 'xdebug.remote_enable=On';
        echo 'xdebug.remote_autostart=On';
        echo "xdebug.remote_host=$(/sbin/ip route|awk '/default/ { print $3 }')";
        echo 'xdebug.force_display_errors=On';
        echo 'xdebug.file_link_format="phpstorm://open?file=%f&line=%l"';
    } > ${PHP_INI_DIR}/conf.d/xdebug.ini

    echo -e '\n> xdebug enabled\n'
fi

if [ "$SYMFONY_ENV" == "prod" ]; then
    chown -R www-data:www-data ${APP_DIR}/var
    rm -rf ${APP_DIR}/bin/sf ${APP_DIR}/web/config.php ${APP_DIR}/web/app_dev.php
fi

/bin/sh -c "${COMMAND}"
