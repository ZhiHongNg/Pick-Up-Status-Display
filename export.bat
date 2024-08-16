@echo off

REM 导出 Docker 镜像到 D 盘的 docker 文件夹
docker save -o D:\docker\mysql_8.0.tar mysql
docker save -o D:\docker\pick-up-status-display_php_latest.tar pick-up-status-display_php

REM 将 import.sh 文件也添加到 D 盘的 docker 文件夹
copy import.sh D:\docker\import.sh

REM 创建 tar 包并将其保存在 D 盘的 docker 文件夹中
tar -cvf D:\docker\pick-up-status-display_project.tar docker-compose.yml D:\docker\mysql_8.0.tar D:\docker\pick-up-status-display_php_latest.tar D:\docker\import.sh

REM 删除导出的 tar 文件和 import.sh 文件
del D:\docker\mysql_8.0.tar
del D:\docker\pick-up-status-display_php_latest.tar
del D:\docker\import.sh

@echo Script completed.
pause
