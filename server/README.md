# docker-laravel 🐳

## Introduction

Build a simple laravel development environment with docker-compose. Compatible with Windows(WSL2), macOS(M1) and Linux.

## Usage

### Laravel install

1. Click [Use this template](https://github.com/ucan-lab/docker-laravel/generate)
2. Git clone & change directory
3. Execute the following command

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

### Laravel setup

1. Git clone & change directory
2. Execute the following command

