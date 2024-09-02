@echo off

REM 导出 Docker 镜像到 D 盘的 docker 文件夹
docker save -o X:\docker\mysql_8.0.tar mysql
docker save -o X:\docker\pick-up-status-display_php_latest.tar pick-up-status-display_php

REM 将 import.sh 文件也添加到 D 盘的 docker 文件夹
copy import.sh X:\docker\import.sh

REM 创建 tar 包并将其保存在 D 盘的 docker 文件夹中
tar -cvf X:\docker\pick-up-status-display_project.tar docker-compose.yml X:\docker\mysql_8.0.tar X:\docker\pick-up-status-display_php_latest.tar X:\docker\import.sh

REM 删除导出的 tar 文件和 import.sh 文件
del X:\docker\mysql_8.0.tar
del X:\docker\pick-up-status-display_php_latest.tar
del X:\docker\import.sh

@echo Script completed.
pause
