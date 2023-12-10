## Introduction

This app uses 
- JWT-Auth for authentication with 'api' as its guard
- Scribe for API Documentation
- SQLite for database


## Requirements

- PHP 8.2
- Composer
- Filinfo extension
- GD Library >= 2.0 OR Imagick PHP extension >= 6.5.7
(GD Library is preferred since it's preinstalled with XAMPP and only requires activation in php.ini, and it meets this project's requirements)
NOTE: Imagick PHP requires PHP 8.1 as there is no official build for PHP 8.2


## Imagick Install (XAMPP)
https://phpandmysql.com/extras/install-imagemagick-and-imagick-xampp/


## Setup

Create .env based on .env.example
Make sure DB is SQLite

Run the following commands
```
composer install
php artisan key:generate --ansi // if this is not run automatically
php artisan jwt:secret
```

DB Setup
```
php artisan migrate
php artisan db:seed
```

Storage Setup
```
php artisan storage:link
```

Run application
```
php artisan serve
```

Go to http://127.0.0.1:8000/docs to access the API documentation

If it's empty, generate the documentation with:
```
php artisan scribe:generate
```