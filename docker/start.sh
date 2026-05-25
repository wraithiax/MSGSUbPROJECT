#!/bin/sh

set -eu

PORT="${PORT:-10000}"

echo "Starting Laravel container on port ${PORT}..."

php artisan config:clear

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  echo "Running database migrations..."
  php artisan migrate --force
fi

if [ "${RUN_SEEDER:-true}" = "true" ]; then
  echo "Running UserSeeder..."
  php artisan db:seed --class=UserSeeder --force
fi

echo "Starting HTTP server..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
