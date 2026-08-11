#!/bin/sh
set -e

echo "=== Migration des tables ==="
php artisan migrate --force

echo "=== Seed admin + groupes sanguins ==="
php artisan db:seed --force --class=DatabaseSeeder

echo "=== Seed comptes de test provinces ==="
php artisan db:seed --force --class=TestProvincesSeeder

echo "=== Lancement Apache ==="
exec apache2-foreground
