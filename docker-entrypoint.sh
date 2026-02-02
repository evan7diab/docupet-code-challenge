#!/bin/sh
set -e

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

php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port=8000
