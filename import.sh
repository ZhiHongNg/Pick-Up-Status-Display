#!/bin/bash

# 加载 Docker 镜像
docker load -i pick-up-status-display_php_latest.tar
docker load -i mysql_8.0.tar

# 启动 Docker 容器
docker-compose up -d

# 脚本完成提示
echo "Docker images loaded and containers started."
