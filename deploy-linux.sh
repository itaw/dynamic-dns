#!/bin/sh
git stash
git pull
composer install
php app/console doctrine:schema:update --force
php app/console cache:clear --env=prod --no-debug
cd ..
chmod -R 777 ./dynamic-dns