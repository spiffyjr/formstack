# FormStack

Simple CRUD for a user system.

## Requirements

 * Virtualbox >= 5.1
 * Vagrant >= 1.8.6
 * Git
 * Root access to your local machine

## Installation

First, update your hosts file to point to the development machine.

`echo 192.168.59.76   testbox.dev www.testbox.dev | sudo tee -a /etc/hosts` 

Next, prepare the project.

```sh
git clone git@github.com:spiffyjr/formstack.git
cd formstack
vagrant up
vagrant ssh
cd /vagrant
cp config/config.php.dist config/config.php
sudo apt-get install php7.0-xml
sudo apt-get install php7.0-sqlite
composer install

# update config/config.php with db password
vi config/config.php

# create tables
mysql -u my_app -p my_app < /vagrant/sql/schema.sql

# verify PSR-2 standards (no output is a success)
 ./vendor/bin/phpcs src/ test/ --standard=PSR2

# run tests
./vendor/bin/phpunit -c test/phpunit.xml --coverage-text
```

## Endpoints

 * `GET /` - Get all users
 * `GET /:id` - Get a single user
 * `POST /` - Create a user
 * `PUT /:id` - Update a user
 * `DELETE /:id` - Delete a user

For `POST` and `PUT` methods you should use JSON similar to below. Note: Password is optional
for the `UPDATE` endpoint.

```json
{
    "firstName": "foo",
    "lastName": "foo",
    "email": "foo@foo.com",
    "password": "foo"
}
```

For security reasons the password is never displayed.

## FAQ

### Why Zend\Diactoros

Diactoros was the first implementation of PSR-7 and is very mature & stable. It implements a low-level server I can 
use to remain PSR-7 compliant. 

### Where's your model!?

For this project I just went with a simple array model. I could have created a `User` class
complete with a `DataMapper` for hydrating DB data but I opted to stick with a basic array
for simplicity. I am, however, a fan of immutable models so a future iteration could have the model 
be a simple `stdClass` implementation.

### Where's your view?!

For APIs the view is the HTTP response. Each handler takes a request and returns a response which, in this case,
is a `Zend\Diactoros\JsonResponse` that implements `Psr\Http\Message\ResponseInterface`.

### Handler, what's this business?! Where's your controller?!

Handlers are single-action controllers. I decided to have them invokable so they're easy to consume (see `index.php`). 
An additional benefit of single-action controllers is that you are able to keep your dependencies light
and only inject what's needed for that request.