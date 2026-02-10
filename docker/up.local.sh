#!/bin/bash

DOCKER_APP_CONTAINER="takegroup-app"

docker compose -f docker-compose.local.yml -p takegroup up -d

docker exec $DOCKER_APP_CONTAINER composer install
docker exec $DOCKER_APP_CONTAINER npm install
docker exec $DOCKER_APP_CONTAINER npm run build

docker exec $DOCKER_APP_CONTAINER ./up.sh

docker exec $DOCKER_APP_CONTAINER php artisan migrate
docker exec $DOCKER_APP_CONTAINER php artisan optimize:clear
docker exec $DOCKER_APP_CONTAINER php artisan key:generate

docker exec $DOCKER_APP_CONTAINER supervisorctl start octane
docker exec $DOCKER_APP_CONTAINER supervisorctl start horizon