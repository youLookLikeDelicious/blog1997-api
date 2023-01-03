#!/bin/bash
# cd /var/www && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1 

cd /var/www

keys=("APP_URL" "REDIS_PASSWORD" "MAIL_HOST" "MAIL_USERNAME" "MAIL_PASSWORD" "DB_PASSWORD" "WECHAT_APP_ID" "WECHAT_SECRET" "GIT_CLIENT_ID" "GIT_SECRET" "WECHAT_PUBLIC_APP_ID" "WECHAT_PUBLIC_SECRET" "GMAP_KEY" "APP_KEY")

cat .env > /tmp/tmp-config

for key in ${keys[@]};
do
	envKey=$(eval echo '$'$key)
	# sed -i  '/'${key}'/c '${key}'='${envKey}'' .env
	sed -i '/^'${key}'/c '${key}'='${envKey}'' /tmp/tmp-config
done
sed -i '/^APP_ENV=/c APP_ENV=production' /tmp/tmp-config

# 如果没有指定app key,自动创建一个app key
num=$(cat /tmp/tmp-config | grep -c ^APP_KEY=$)
if [ $num -eq 1 ]
then
	appKey=$(php artisan key:generate --force --show)
	sed -i '/^APP_KEY=/c APP_KEY='${appKey}'' /tmp/tmp-config
	# echo "Please remember you app key: '${appKey}'"
	export APP_KEY=$appKey
fi
cat /tmp/tmp-config > .env

php artisan migrate --force
php artisan db:seed --force
php artisan sitemap:init
php artisan config:cache
php artisan view:cache
php artisan master:create $MASTER_EMAIL admin123

supervisord -c /etc/supervisor/conf.d/laravel-worker.conf
exec php-fpm