# This application is not complete, however it contains a foundation so that one can proceed to the desired implementation. 
<img src="https://apaliampelos.me/assets/images/github/vehicle-telemetry-and-diagnosis-device/test_telematic_app.png" alt="Telematic App"/>

## Installation

```bash
$ git clone https://github.com/sonole/Vehicle-Telemetry-and-Diagnosis-System.git
$ cd  Vehicle-Telemetry-and-Diagnosis-System/server
$ docker compose up -d
$ docker exec -it server-app-1 /bin/bash
$ composer install
```

While you are still on the shell of the docker container you should edit the .env file and add at the bottom the GOOGLE_MAPS_API_KEY
```bash
$ nano .env
```

Note that database should already be migrated and seeded

Now that everything is ok you can start the tcp services to accept packages 
```bash
$ php artisan tcp:start 

```
This will wait for packages until stopped with the following command
```bash
$ php artisan tcp:stop
```
