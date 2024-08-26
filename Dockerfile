# 使用官方PHP镜像，包含Nginx和Redis安装
FROM php:8.1-fpm
# 更新包列表并安装 MySQL 客户端
RUN apt-get update && \
    apt-get install -y default-mysql-client && apt-get install -y tcpdump && \
    apt-get clean
# 安装必要的工具和库
RUN apt-get install -y \
    nginx \
    redis-server \
    curl \
    vim \
    git \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# 安装PHP Redis扩展
RUN pecl install redis && docker-php-ext-enable redis

# 安装Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 配置Nginx
RUN rm /etc/nginx/sites-enabled/default
COPY ./nginx.conf /etc/nginx/sites-enabled/default

# 复制当前目录内容到容器中
COPY . /var/www/html

# 设置工作目录
WORKDIR /var/www/html
RUN mkdir -p /var/www/html/runtime && chmod -R 777 /var/www/html/runtime && chmod -R 777 /var/www/html/public
# 运行Composer install
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

RUN su www
RUN composer install --ignore-platform-reqs
RUN cp /var/www/html/websocket.service /etc/systemd/system/websocket.service && cp -r /var/www/html/frontEnd/dist/build/h5/* /var/www/html/public/ && mv /var/www/html/public/index.html /var/www/html/public/parking.html



# 暴露端口80
EXPOSE 80
EXPOSE 8080
EXPOSE 6379

# 添加 init-db.sh 脚本到容器中
COPY ./init-db.sh /init-db.sh
RUN chmod +x /init-db.sh

# 确保 start.sh 调用 init-db.sh
COPY ./start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
#build命令
#docker build -t  pick-up-status-display_php .
#run命令
#docker run -d -p 6464:80 --name  pick-up-status-display_php  pick-up-status-display_php

#打包
#docker save -o D:\docker\mysql_8.0.tar 23b013c7c67d
#docker save -o D:\docker\pick-up-status-display_php_latest.tar e8896d9bb1d7
#tar -cvf D:\docker\pick-up-status-display_project.tar docker-compose.yml D:\docker\mysql_8.0.tar D:\docker\pick-up-status-display_php_latest.tar
#导入
#docker load -i pick-up-status-display_php_latest.tar
#docker load -i mysql_8.0.tar
#docker-compose up -d
