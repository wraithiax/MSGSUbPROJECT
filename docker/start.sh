#!/bin/sh

set -eu

PORT="${PORT:-10000}"

echo "Starting Laravel container on port ${PORT}..."

php artisan config:clear

echo "Starting HTTP server..."
php artisan serve --host=0.0.0.0 --port="${PORT}" &
SERVER_PID="$!"

echo "Running database migrations..."
php artisan migrate --force

echo "Running UserSeeder..."
php artisan db:seed --class=UserSeeder --force

echo "Database setup complete."
wait "$SERVER_PID"
