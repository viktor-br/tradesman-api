# About
Simple tradesman jobs management API.

# Documentation
Please see documentation online [https://app.swaggerhub.com/apis/viktor-br/tradesman-jobs-api/1.0#/](https://app.swaggerhub.com/apis/viktor-br/tradesman-jobs-api/1.0#/)

# Run application
Requirements: docker and docker-compose

Clone repository
```bash
git clone git@github.com:viktor-br/tradesman-api.git
```

and go inside the directory.

## Build docker images
We need to build two docker image, not to rely on 3rd party images.

Build docker image for PHP cli with composer:
```bash
docker build \
    -t viktor-brusylovets/php-with-composer:v1.0 \
    infrastructure/docker/php-with-composer
```

Build docker image for application code:
```bash
docker build \
    -f ./infrastructure/docker/application/Dockerfile \
    -t viktor-brusylovets/tradesman-api:v1.0 \
    .
```

## Dependencies installation
Install dependencies with using docker container:
```bash
docker run \
    -it --rm \
    -w /usr/src/tradesman-api \
    -v `pwd`:/usr/src/tradesman-api \
    viktor-brusylovets/php-with-composer:v1.0 \
    bash -c 'composer install'
```

## Running application in dev mode
By default dev application is running on 8008 port. In case of port conflict, ports binding in docker-composer.yml should be adjusted.
Run application in dev mode (press Ctrl+C to stop running containers at the end):
```bash
docker-compose -p dev up
```

Propagate dev database with default data:
```bash
docker run \
    -it \
    --rm \
    -v `PWD`:/usr/src/tradesman-api \
    -w /usr/src/tradesman-api \
    --net dev_tradesmannet_dev \
    viktor-brusylovets/php-with-composer:v1.0 \
    bash -c 'mysql -h tradesman-api-dev-mysql -u docker --password=12345 tradesman_dev < tests/_data/dump.sql'
```

You can test endpoint with curl:
```bash
docker run \
    -it \
    --rm \
    -v `PWD`:/usr/src/tradesman-api \
    -w /usr/src/tradesman-api \
    --net dev_tradesmannet_dev \
    viktor-brusylovets/php-with-composer:v1.0 \
    bash -c 'curl http://tradesman-api-dev-nginx:8008/v1/postcode/10115'
```
Hopefully you'll see:
```json
{
    "items": [
        "Berlin"
    ]
}
```

To get access to API endpoint in browser [http://www-docker.tradesman-api:8008/v1/postcode/10115](http://www-docker.tradesman-api:8008/v1/postcode/10115), you need to add next line to /etc/hosts:
```text
127.0.0.1 www-docker.tradesman-api
```

## Testing
### Unit tests
```bash
docker run \
    --rm \
    -v `PWD`:/usr/src/tradesman-api \
    -w /usr/src/tradesman-api \
    viktor-brusylovets/php-with-composer:v1.0 \
    bash -c 'vendor/bin/codecept run unit'
```

### Acceptance tests
Testing environment is separated and could be running simultaneously with dev environment.
Run application in test mode (press Ctrl+C to stop running containers at the end):
```bash
docker-compose -f docker-compose-testing.yml -p testing up
```

Run acceptance tests:
```bash
docker run \
    --rm \
    -v `PWD`:/usr/src/tradesman-api \
    -w /usr/src/tradesman-api \
    --net testing_tradesmannet_testing \
    viktor-brusylovets/php-with-composer:v1.0 \
    bash -c 'vendor/bin/codecept run api'
```

## Docker artifacts clean up
You can use next commands to remove containers, images and networks at the end.
```bash
docker image rm viktor-brusylovets/tradesman-api:v1.0
docker image rm viktor-brusylovets/php-with-composer:v1.0
docker network rm dev_tradesmannet_dev
docker network rm testing_tradesmannet_testing
docker rm tradesman-api-testing-nginx
docker rm tradesman-api-testing-app
docker rm tradesman-api-testing-mysql
docker rm tradesman-api-dev-nginx
docker rm tradesman-api-dev-app
docker rm tradesman-api-dev-mysql
```
