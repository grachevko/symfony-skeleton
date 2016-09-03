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
    --no-composer)
    COMPOSER=false
    ;;
    composer)
    BUILD_PARAMS=false
    COMPOSER=false
    XDEBUG=false
    COMMAND=${COMMAND}' '${i}
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


if [ "$SYMFONY_ENV" == "dev" ]; then
    ln -sf ${APP_DIR}/bin/console /usr/local/bin/sf

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
fi

if [ "$SYMFONY_ENV" == "prod" ]; then
    chown -R www-data:www-data ${APP_DIR}/var
    cp -rfL  ${APP_DIR}/web/* ${NGINX_WEB_DIR}/
    rm -rf ${NGINX_WEB_DIR}/*.php
fi

/bin/sh -c "${COMMAND}"
