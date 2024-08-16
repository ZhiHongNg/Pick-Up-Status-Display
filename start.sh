#!/bin/bash

# 启动PHP-FPM
php-fpm &

# 启动Redis
service redis-server start
#sudo systemctl daemon-reload
sudo systemctl start websocket.service
/init-db.sh


rm /etc/nginx/sites-enabled/default
cp ./nginx.conf /etc/nginx/sites-enabled/default
# 启动Nginx
#nginx -g 'daemon off;'
service nginx start
php websocket_server.php
#tail -f /var/log/nginx/access.log
