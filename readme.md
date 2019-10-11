# Flyht Sample Software

## Requirements

- MySQL Server
- PHP 7 or higher
- node.js (Tested on v10.16.0)

## Installation

- Run this git command
```sh
git clone https://github.com/kaisbitar/FlyhtTest.git
```
- Create a database on your local and insert it's name in the `.env` file by renaming the file `.env.example` and edit the following lines in `.env`

```sh
DB_DATABASE= your-db-name
```
- edit your database credentials
```sh
DB_USERNAME=root
DB_PASSWORD=
```

- Navigate in your terminal to the root directory of the project then simply run the following commands:

```sh
composer install
```
- After the installation takes place, run:
```sh
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```
- Open another terminal and run:
```sh
npm install
npm run watch
```
- Go in your browser to `localhost:8000` and see the results
