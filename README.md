## Introduction

This app uses 
- JWT-Auth for authentication with 'api' as its guard
- Scribe for API Documentation
- SQLite for database


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

Run application
```
php artisan serve
```

Go to http://127.0.0.1:8000/docs to access the API documentation

If it's empty, generate the documentation with:
```
php artisan scribe:generate
```