Symfony Micro Service Skeleton
=

## Quick install
```
git clone https://github.com/grachevko/service-skeleton.git
cd service-skeleton
docker-compose run --rm --no-deps app composer update
```

### Instaleled Packages

* doctrine/doctrine-migrations-bundle
* guzzlehttp/guzzle
* ramsey/uuid-doctrine
* pagerfanta/pagerfanta


### Build drone secure
```
drone secure --in secure.yml --repo {repo}
```
