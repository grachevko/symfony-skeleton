#!/bin/sh

set -e

bin/symfony_requirements

php-cs-fixer fix --dry-run --level symfony ./src/

bin/console doctrine:schema:validate

phpunit
