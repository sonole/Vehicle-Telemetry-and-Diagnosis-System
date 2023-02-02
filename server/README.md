# docker-laravel 🐳

## Introduction
## Usage
### Laravel install

//maybe remove npm from php/Dockerfile
```bash
$ git clone https://github.com/sonole/Vehicle-Telemetry-and-Diagnosis-System.git
$ cd  Vehicle-Telemetry-and-Diagnosis-System/server
$ docker compose up -d
$ docker exec -it server-app-1 /bin/bash
$ composer install
$ php artisan migrate
$ php artisan db:seed
$ nano .env
```



