#!/bin/sh

echo "Preparing MySQL directories..."

mkdir -p /run/mysqld
mkdir -p /var/lib/mysql

echo "Starting MariaDB..."

mariadbd --user=root --datadir=/var/lib/mysql &

sleep 5

echo "Creating database..."

mariadb -u root -e "CREATE DATABASE IF NOT EXISTS citizen_management;"

echo "Running migrations..."

php artisan migrate --force

echo "Starting Laravel..."

php artisan serve --host=0.0.0.0 --port=${PORT:-8000}