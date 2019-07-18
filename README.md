# TODO

....

### Dev notes

```
$ docker run --rm -itp 8000:8000 -v $PWD:/app -w /app php:7.1-alpine sh
$ php -d memory_limit=-1 composer.phar install
$ php bin/phpunit
$ php bin/console server:run 0.0.0.0:8000
```
