#!/usr/bin/env bash
set -e

wait_for_database() {
    echo "Waiting for database..."

    for _ in $(seq 1 30); do
        if php -r "
            try {
                if (\$url = getenv('DB_URL')) {
                    new PDO(\$url);
                    exit(0);
                }
                \$host = getenv('DB_HOST');
                if (! \$host) {
                    exit(0);
                }
                \$dsn = sprintf(
                    'pgsql:host=%s;port=%s;dbname=%s',
                    \$host,
                    getenv('DB_PORT') ?: '5432',
                    getenv('DB_DATABASE') ?: 'postgres'
                );
                new PDO(\$dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
                exit(0);
            } catch (Throwable \$e) {
                exit(1);
            }
        "; then
            echo "Database is ready."
            return 0
        fi

        sleep 2
    done

    echo "Database connection timed out."
    exit 1
}

wait_for_database

php artisan config:clear

php artisan migrate --force

if [ "${SEED_DATABASE:-false}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
