# Commission task andersen lab

## Instalation
- To install project download clone it to Your computer
- run `docker-compose up -d` if Dockerfile has changed You need to run `docker-compose up -d --build`
- run `docker exec -it app composer install` to install dependencies

## Usage
There's one command to handle commission task. To run it write in console in project directory:

`docker exec -it app php bin/console app:deposit_withdraw_processor_command <filepath.csv>`

example:

`docker exec -it app php bin/console app:deposit_withdraw_processor_command input/example.csv`

## Test
There's functional tests for command. When docker is running You can run functional tests by this command:

`docker exec -it app env APP_ENV="test" php bin/phpunit`

You can also run full testing with cs-checker:

`docker exec -it app env APP_ENV="test" composer test`