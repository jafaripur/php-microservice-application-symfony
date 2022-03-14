# Microservice Application Skeleton

![prod-build](https://github.com/jafaripur/php-microservice-application-symfony/actions/workflows/build-prod.yml/badge.svg)
![test](https://github.com/jafaripur/php-microservice-application-symfony/actions/workflows/run-test.yml/badge.svg)

`Symfony` console application used to write our processors of methods to responsible to client calling.

This application a template for microservice application and implement four methods of [jafaripur/php-microservice](https://github.com/jafaripur/php-microservice). Another library created for using this microservice methods [jafaripur/php-microservice-user-service](https://github.com/jafaripur/php-microservice-user-service).

For consuming and receiving data:

```bash

php bin/console user-service:listen

```

For sending tests messages, By using this client library (`jafaripur/php-microservice-user-service`):

```bash

php bin/console user-service:send-test

```

## Create project

```bash

composer create-project "jafaripur/php-microservice-application-symfony dev-master" micro3

```

## Production Build

This application can be run with `roadrunner` service plugin in production with Dockerfile `docker/Dockerfile.prod`, Production build with docker:

```bash

export DOCKER_BUILDKIT=1 && docker build -f "./docker/Dockerfile.prod" -t "micro3-prod:latest" .

```

After building, we can create a container or docker swarm service. The production docker image runs with `RoadRunner`. In this example we use this configuration for [RoadRunner](https://github.com/roadrunner-server/roadrunner), config exist in `.rr.yaml` file.

```yml

version: "2.7"

service:
  topics:
    command: "php bin/console user-service:listen first-consumer"
    process_num: 10
    exec_timeout: 0
    remain_after_exit: true
    restart_sec: 5

  emits:
    command: "php bin/console user-service:listen second-consumer"
    process_num: 2
    exec_timeout: 0
    remain_after_exit: true
    restart_sec: 5

logs:
  mode: production
  encoding: console

```

With this RoadRunner service plugin we can run several consumer with several instance.

For creating docker container from builded image:

```bash

docker run -d --init \
    --name micro3-container \
    --restart unless-stopped \
    micro3-prod:latest

```

And for swarm service:

```bash

docker service create --name "micro3-service" \
    --replicas 2 \
    --update-delay 10s \
    micro3-prod:latest

```

## Test

```bash

# Build test containers
docker-compose build --build-arg APP_SECRET=123456

# Run test
docker-compose up micro

# Stop and remove created containers
docker-compose down

```