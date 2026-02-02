#!/bin/sh
set -e

# Write environment variables to .env file (needed for artisan serve child processes)
cat > .env <<EOF
APP_KEY=$APP_KEY
API_KEY=$API_KEY
DB_CONNECTION=$DB_CONNECTION
DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_DATABASE=$DB_DATABASE
DB_USERNAME=$DB_USERNAME
DB_PASSWORD=$DB_PASSWORD
EOF

# Wait for MySQL
until php -r "
  try {
    new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD')
    );
    exit(0);
  } catch (Throwable \$e) {
    exit(1);
  }
" 2>/dev/null; do
  echo "Waiting for database..."
  sleep 2
done

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
  php artisan key:generate --force
fi

# Clear all caches (including bootstrap cache which may have stale data from build)
php artisan optimize:clear

php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port=8000
