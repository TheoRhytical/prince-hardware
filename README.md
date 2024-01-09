## Introduction

This app uses 
- Frontend: Vue, InertiaJS, BootstrapCSS, TailwindCSS
- Backend: Laravel


## Requirements

- PHP 8.2
- Composer
- Filinfo extension
- GD Library >= 2.0 OR Imagick PHP extension >= 6.5.7
(GD Library is preferred since it's preinstalled with XAMPP and only requires activation in php.ini, and it meets this project's requirements)
NOTE: Imagick PHP requires PHP 8.1 as there is no official build for PHP 8.2
- Node >= 18.0 (for Vue3 w/ Inertia)

## Most basic download/install guide
- Download and install [XAMPP](https://www.apachefriends.org/download.html) with PHP 8.2: Also comes with MySQL
- Download and install [Composer](https://getcomposer.org/download/)
- Download and install [NodeJS](https://nodejs.org/en/download): Also comes with NPM

## Imagick Install (XAMPP)
https://phpandmysql.com/extras/install-imagemagick-and-imagick-xampp/


## Setup
(This assumes that the required installations are done)
Create .env based on one of the .env.examples
If XAMPP is downloaded, you can use .env.example.mysql and setup mysql
If you're not comfortable with either MySQL or PostgreSQL setup, there is .env.example.sqlite that uses sqlite for DB
To access the SQLite DB in a separate GUI for debugging purposes, [SQLiteStudio](https://sqlitestudio.pl/) is recommended
If you decide to use PgSQL, then I assume you know your shit, so I won't include instructions anymore for this

Run one of the following commands based on your choice
```
cp .env.example.sqlite .env
cp .env.example.mysql .env
cp .env.example.pgsql .env
```

Then run the following commands
```
composer install
npm install
php artisan key:generate --ansi // if this is not run automatically
```

DB Setup
NOTE: Seeding will take a few seconds to a few minutes as it will be downloading fake images as signature placeholders
- In .env, updated SEEDED_CUSTOMERS variable to the amount of starting customers you want. Default is 20
```
php artisan migrate
php artisan db:seed
```

Storage Setup
```
php artisan storage:link
```

Run application (run the commands in two separate terminals)
```
npm run dev
php artisan serve
```

The app can be access from http://127.0.0.1:8000/
Login credentials are:
```
Admin
email: admin@example.com
pass: admin123
```
```
Customer
email: customer@example.com
pass: customer123
```