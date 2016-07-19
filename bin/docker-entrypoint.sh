#!/bin/sh

set -e

composer run-script post-install-cmd --no-interaction --quiet
console doctrine:migrations:migrate --no-interaction --allow-no-migration

chmod 775 -R $APP_DIR
chmod 777 -R $APP_DIR/var
chmod +x -R $APP_DIR/bin/*

exec "$@"
