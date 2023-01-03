#!/bin/bash
num=$(cat .env | grep -c ^APP_KEY=$)
if [ $num -eq 1 ]
then
	php artisan key:generate --force
	sed -n '/^APP_KEY/p' .env | sed 's/APP_KEY=/Please remember your app key: /'
fi
