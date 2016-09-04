#!/bin/sh

set -e

docker-compose run --rm --entrypoint composer app "$*"
