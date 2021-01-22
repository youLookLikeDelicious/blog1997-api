# 生产模式 (参考)

你可以选择使用docker,将应用程序部署到服务器上.在这提供一个参考,从构建docker镜像开始.除此之外,你还需要创建mysql和redis容器,没有什么特别的要求.

## 构建docker镜像

目前采用的方案,是将源代发分别打包到了nginx镜像和php-fpm镜像中.期待出现更好的意见和建议

```bash
# nginx镜像
docker build -t your-repository:1.0.0 --target nginx -f docker/DockerFile .
# php-fpm 镜像
docker build -t you-repository:1.0.0 --target app -f docker/DockerFile .
```
## 创建一个服务
### 建议使用docker的config设置相关的env和php.ini
```bash
docker config create laravel.env /local-path/.env
docker config create php.ini /local-path/php.ini
```
创建一个php-fpm service
```bash
docker service create \
--name service-name \
--network net-work-name \
--replicas 1 \
--config src=laravel.env,target=/var/www/.env \
--config src=php.ini,target=/usr/local/etc/php/php.ini \
your-image:tag
```

创建一个nginx service(需要php-fpm和nuxt项目都部署完毕后再创建该服务)
```bash
docker service create \
--name nginx \
--network blog \
--constraint node.labels.region==*** \
--replicas 1 \
--publish mode=host,target=80,published=80 \
--update-failure-action continue \
--update-delay 10s \
--mount type=bind,source=/var/log/nginx,target=/var/log/nginx \
--config src=nginx-server,target=/etc/nginx/conf.d/default.conf \
--config src=nginx-http,target=/etc/nginx/nginx.conf \
you-nginx-image:tag
```

## 或是创建一个容器
```docker
docker run -itd \
--name container-name \
--network net-work-name \
--mount type=bind,source=/local-path/.env,target=/container-project.env \
--mount type=bind,source=/local-path/php.ini,target=/container-project.ini \
your-image:tag
```

## 初始化程序
```bash
php artisan config:cache
php artisan view:cache
php artisan migrate
php artisan db:seed --class AuthTableSeeder
php artisan db:seed --class RoleAuthTableSeeder
php artisan db:seed --class RoleTableSeeder
php artisan db:seed --class SystemSettingSeed
php artisan sitemap:init
php artisan master:create email@email.com
```