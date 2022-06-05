<h1>How To Run Application</h1>

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## Run with Docker:

- clone the project from git

- install docker in your system

- in the root directory of project run following command in terminal

docker-compose up --build

- wait until all of the installations finish

- now you can open the project with following url and port on any browser

localhost:89


## Running Test Using Docker

- after running docker using "Run with Docker" instruction

- in the other terminal run "docker exec commissionCalculator_php php artisan test"


## Run Manually:

its highly recommended to run the project using "Run With Docker" instruction and this explanation is only for completing README file

- install php version 8.1

- install composer version 2

- make a copy of ".env.example" file in the root directory and name it ".env"

- in the root directory of project run "composer install"

- when composer installation finished continue the following instruction:

- in the root directory of project run "php artisan key:generate"

- in the root directory of project run "php artisan serve"

- open given route in the browser and use the application

## Run tests Manually:

- to run the tests, after installing composer using "composer install" in the project root directory

- make a copy of ".env.example" and name it ".env"

- in the root directory of project run "php artisan test"

***** please note that php and composer that are mentioned in "Run Manually" must exist on system

## Description of Application

- this application is created to calculate commission of withdraw and deposit for two type of users that each user have its own logic

- all the variables that is used in the logic is placed in the ".env" file so change in values does not need any change in the code

- also application use service and actions in its structure so change in code will not effect on the core

- each situation of commission have its own function and change in one logic does not effect on others

- for more information about project logic please check the test instruction on gist.


## License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Developer Contact Information
- full name: Mohammadamin Gheibi
- email: gheibi.amin@gmail.com
