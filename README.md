# Commission calculation symfony-based project example

## Installation

Make sure you have installed ```docker``` & ```docker-compose``` on your OS.

Note: This docker-compose build was tested on Ubuntu 20.04 and any issues caused on your OS you should resolve on your own.

Create new .env file inside ```sf/``` folder and copy default values from ```.env.dist```

After ```docker``` & ```docker-compose``` installed - inside of root directory run:

```
docker-compose up -d
```

After containers is up - ```composer install``` will be executed in background. It will take some time to download packages.

```exec-into-container.sh``` - quick alias for exec into php-fpm docker container.

By default, server exposed on 8080 port on host machine. You could overwrite port if it causes conflicts in your network.
## Usage

### How to run fee calculations

There are 2 ways to run calculations:
1. API - You must use (on your preference) any API development tool. Single require to the tool - make sure it could send files in form to the endpoint.
   * Endpoint -  ```POST http://127.0.0.1:8080/calculate-fee```
   * Input file param name: ```file```

2. CLI
   * If you have unix-system with bash support or any tool that could run ".sh" scripts - ```run-fee-calculation.sh```
   * In case when your console is not supporting bash execution - make your way to run the following:
```docker-compose exec php-fpm php ./bin/console fee:calculate```
   * Also, you could run calculations directly in container: ```php ./bin/console fee:calculate```  


### How to run tests

* Unix execution script: ```run-tests.sh```
* Native command: ```php ./vendor/bin/phpunit```

### How to test against custom input

There are 3 ways to test against custom input values:

* Change ```input.csv``` directly in sf/storage directory.
* Use API method with a custom file provided.
* Put a new file in storage folder and execute CLI cmd inside docker container with a filename provided as following:
```php ./bin/console fee:calculate --file="storage/5kRowInput.csv"```
  