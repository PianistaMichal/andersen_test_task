# Commission task andersen lab

##Instalation
- To install project download clone it to Your computer
- run `composer install` to install dependencies

##Usage
There's one command to handle commission task. To run it write in console in project directory:

`php bin/console app:deposit_withdraw_processor_command <filepath.csv>`

example:

`php bin/console app:deposit_withdraw_processor_command test.csv`

##Docker
There's docker configuration. it's required to run functional tests. To run docker use command `docker-compose up -d`

##Test
There's functional tests for command. When docker is running You can run functional tests by this command:

`docker exec -it app php bin/phpunit`

You can also run full testing with cs-checker:

`docker exec -it app composer test`