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
$ docker run --rm -itp 4321:4321 -v $PWD:/app -w /app php:7.4-alpine sh
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

To run chrome disabling CORS security check and test the application with cleancoders_openchat_webclient:

```
$ google-chrome-stable --disable-web-security --incognito --user-data-dir /tmp/ http://localhost:5000
```

#### Refactoring notes

- foreign key on user table for posts and followings?
- test SqlLite repositories one method at time
- remove *public* from test methods?
- refactor SqlLitePostRepositoryScenarioTest: too many asserts
