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

Next, install the project.

```sh
git clone git@github.com:spiffyjr/formstack.git
cd formstack
vagrant up
vagrant ssh
cd /vagrant
composer install
```

For a first time installation run the schema file located in `sql/`.

`mysql -u my_app -p < /vagrant/sql/schema.sql`

## Endpoints

`GET /` - Get all users
`GET /:id` - Get a single user
`POST /` - Create a user
`PUT /:id` - Update a user
`DELETE /:id` - Delete a user

For `POST` and `PUT` methods you should use JSON similar to the following:

```json
{
    "first_name": "foo",
    "last_name": "foo",
    "email": "foo@foo.com",
    "password": "foo"
}
```

For security reasons the password is never displayed.