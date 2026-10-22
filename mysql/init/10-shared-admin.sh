#!/bin/sh
set -eu

case "$APP_ADMIN_USER" in
  ''|*[!a-zA-Z0-9_]*)
    echo "APP_ADMIN_USER must contain only letters, numbers, and underscores" >&2
    exit 1
    ;;
esac

mysql --protocol=socket -uroot -p"$MYSQL_ROOT_PASSWORD" <<SQL
CREATE USER IF NOT EXISTS '${APP_ADMIN_USER}'@'%' IDENTIFIED BY '${APP_ADMIN_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${MYSQL_DATABASE}\`.* TO '${APP_ADMIN_USER}'@'%';
SQL