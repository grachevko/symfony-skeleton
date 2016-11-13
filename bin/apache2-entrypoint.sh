#!/bin/sh

cp ${APP_DIR}/app/config/apache.conf ${APACHE_CONFDIR}/sites-enabled/000-default.conf

a2enmod rewrite

chown -R www-data:www-data ${APP_DIR}/var

apache2-foreground
