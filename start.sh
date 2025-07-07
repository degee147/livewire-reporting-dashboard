#!/bin/bash

docker-compose up -d --build
docker exec -it laravel-app composer install
docker exec -it laravel-app php artisan migrate --seed
docker exec -it laravel-app php artisan key:generate
