#!/usr/bin/env bash
set -euo pipefail

PHP_BIN="/usr/local/bin/php"

if [ ! -x "$PHP_BIN" ]; then
    PHP_BIN="$(command -v php || true)"
fi

if [ -z "$PHP_BIN" ]; then
    echo "ERROR: PHP not found. Render Runtime must be set to Docker (not Node/Shell)."
    exit 1
fi

wait_for_database() {
    if [ "${DB_CONNECTION:-}" != "pgsql" ] && [ -z "${DB_URL:-}" ]; then
        return 0
    fi

    echo "Waiting for database..."

    for _ in $(seq 1 45); do
        if [ -n "${DB_URL:-}" ] && command -v psql >/dev/null 2>&1; then
            if psql "$DB_URL" -c "SELECT 1" >/dev/null 2>&1; then
                echo "Database is ready."
                return 0
            fi
        elif [ -n "${DB_HOST:-}" ] && command -v pg_isready >/dev/null 2>&1; then
            if pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-postgres}" >/dev/null 2>&1; then
                echo "Database is ready."
                return 0
            fi
        else
            if "$PHP_BIN" artisan db:show >/dev/null 2>&1; then
                echo "Database is ready."
                return 0
            fi
        fi

        sleep 2
    done

    echo "Database connection timed out."
    exit 1
}

cd /var/www/html

wait_for_database

"$PHP_BIN" artisan config:clear
"$PHP_BIN" artisan migrate --force

if [ "${SEED_DATABASE:-false}" = "true" ]; then
    "$PHP_BIN" artisan db:seed --force
fi

"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

echo "Starting server on port ${PORT:-8080}..."
exec "$PHP_BIN" artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
