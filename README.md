# subnauticapp
Test technique (Backend)

## Requirements
- php7.2
- composer
- docker-compose (if no have database)

## Intallation

### docker-compose if need DATABASE

In app folder run this command:

```
docker-compose up -d
```

### composer

In app folder run this command:

```
composer install
```

## Configuration


Change var DATABASE_URL in `.env` with ip of *docker machine*

```
DATABASE_URL=mysql://root:@<ip>:3306/sub_nautic_app?serverVersion=5.7
```

### Doctrine

Use doctrine command to create database. Run this commands :
```
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:schema:update --force
```

### Launch server

``` 
php -S 127.0.0.1:8600 public/index.php
```
