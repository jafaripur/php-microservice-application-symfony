#!/bin/sh

if [[ ! -e /app/.env.local ]]; then

echo "
APP_SECRET=$1
APP_ENV=prod
APP_DEBUG=off
" > /app/.env.local

fi