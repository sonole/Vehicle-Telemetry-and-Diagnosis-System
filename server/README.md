## Installation

//maybe remove npm from php/Dockerfile
```bash
$ git clone https://github.com/sonole/Vehicle-Telemetry-and-Diagnosis-System.git
$ cd  Vehicle-Telemetry-and-Diagnosis-System/server
$ docker compose up -d
$ docker exec -it server-app-1 /bin/bash
$ composer install
```

While you are still on the shell of the docker container you should edit the .env file
```bash
$ nano .env
```

Migrate the database, and seed it to test the app
```bash
$ nano .env
```

Now that everything is ok you can start the tcp services to accept packages 
```bash
$ php artisan tcp:start 

```
This will wait for packages until stopped with the following command
```bash
$ php artisan tcp:stop
```
