#!/bin/sh

if [[ ! -e /app/.env.test.local ]]; then

echo "
APP_SECRET=$1
QUEUE_AMQP_DSN=amqp+rabbitmq://guest:guest@php-microservice-rabbitmq-test:5672/test
" > /app/.env.test.local

fi