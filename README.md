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