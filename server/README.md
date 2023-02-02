# docker-laravel 🐳

## Introduction

Build a simple laravel development environment with docker-compose. Compatible with Windows(WSL2), macOS(M1) and Linux.

## Usage

### Laravel install

1. Click [Use this template](https://github.com/ucan-lab/docker-laravel/generate)
2. Git clone & change directory
3. Execute the following command

```bash
$ docker compose up -d
$ docker exec -it tele-app-1 /bin/bash
$ composer install
$ php artisan migrate
$ php artisan db:seed
$ nano .env
```

### Laravel setup

1. Git clone & change directory
2. Execute the following command

