#!/bin/sh

echo "Starting MariaDB..."

mysqld --datadir=/var/lib/mysql --user=root &

sleep 5

echo "Creating database..."

mysql -u root -e "CREATE DATABASE IF NOT EXISTS citizen_management;"

echo "Running migrations..."

php artisan migrate --force

echo "Starting Laravel..."

php artisan serve --host=0.0.0.0 --port=${PORT:-8000}