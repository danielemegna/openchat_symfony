# Open Chat Backend Kata

Develop a rest backend for the Open Chat project following api specification in material/APIs.yaml.

## About this Kata

This Kata is used by Robert C. Martin and Sandro Mancuso in "London vs. Chicago" cleancoders serie:

* https://cleancoders.com/videos?series=comparativeDesign
* https://github.com/sandromancuso/cleancoders_openchat/tree/starting-point
* https://github.com/sandromancuso/cleancoders_openchat_webclient
* https://github.com/danielemegna/cleancoders_openchat/tree/openchat-hexagonal

## Dev notes


Prepare dev cli environment
```
$ docker run --rm -itp 4321:4321 -v $PWD:/app -w /app php:7.1-alpine sh
$ php -d memory_limit=-1 composer.phar install
```

Run tests with
```
$ php bin/phpunit
```

or run foreground application with
```
$ php bin/console server:run 0.0.0.0:4321
```

Use tmuxinator
```
$ tmuxinator local
```

or tmuxp
```
$ tmuxp load .tmuxp.yml
```

#### Refactoring notes

- remove function gen_uuid duplication
- remove serialize / deserialize duplications
- remove assertIsAValidUUID duplication
- merge status code and content type E2E assertions
