#!/bin/bash

# 等待 MySQL 服务启动
while ! mysqladmin ping -h"db" --silent; do
    echo "等待 MySQL 启动..."
    sleep 2
done
#!/bin/bash

# Database connection details
HOST="db"
USER="janjanjan"
PASSWORD="janjan123"
DBNAME="HeberosiaStore"
SQLFILE="/var/www/html/sql/HeberosiaStore.sql"

# Check if the database exists
DB_EXISTS=$(mysql -hdb -uroot -proot -e "SHOW DATABASES LIKE '$DBNAME';" | grep "$DBNAME" > /dev/null; echo "$?")

# If the database doesn't exist, create it
if [ $DB_EXISTS -eq 1 ]; then
  echo "Database $DBNAME does not exist. Creating..."
  mysql -hdb -uroot -proot -e "CREATE DATABASE HeberosiaStore";
  echo "Database $DBNAME created."
else
  echo "Database $DBNAME already exists."
fi

# Import the SQL file into the database
echo "Importing $SQLFILE into $DBNAME..."
mysql -hdb -uroot -proot  HeberosiaStore < /var/www/html/sql/HeberosiaStore.sql
echo "Import completed."
