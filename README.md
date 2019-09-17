# Open Chat Backend Kata

Develop a rest backend for the Open Chat project following api specification in material/APIs.yaml.

## About this Kata

This Kata is used by Robert C. Martin and Sandro Mancuso in "London vs. Chicago" cleancoders serie:

* https://cleancoders.com/videos?series=comparativeDesign
* https://github.com/sandromancuso/cleancoders_openchat/tree/starting-point
* https://github.com/sandromancuso/cleancoders_openchat_webclient
* https://github.com/danielemegna/cleancoders_openchat/tree/openchat-hexagonal

### Dev notes

```
$ docker run --rm -itp 4321:4321 -v $PWD:/app -w /app php:7.1-alpine sh
$ php -d memory_limit=-1 composer.phar install
$ php bin/phpunit
$ php bin/console server:run 0.0.0.0:4321
```

With tmuxinator:
```
$ tmuxinator local
```

#### Refactoring notes

- add Error suffix for UsernameAlreadyUsed and InvalidCredentials class
- remove function gen_uuid duplication
- remove serialize / deserialize duplications
