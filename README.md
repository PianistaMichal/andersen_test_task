# Commission task andersen lab

## Instalation
- To install project download clone it to Your computer
- run `docker-compose up -d`
- run `docker exec -it app composer install` to install dependencies
- copy .env.prod to .env and fill data

## Usage
There's one command to handle commission task. To run it write in console in project directory:

`docker exec -it app php bin/console app:deposit_withdraw_processor_command <filepath.csv>`

example:

`docker exec -it app php bin/console app:deposit_withdraw_processor_command tests/mocks/example.csv`

## Test
There's functional tests for command. First copy `.env.test` to `.env`. When docker is running You can run functional tests by this command:

`docker exec -it app php bin/phpunit`

You can also run full testing with cs-checker:

`docker exec -it app composer test`