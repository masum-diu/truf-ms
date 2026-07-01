#!/usr/bin/env bash
set -euo pipefail

resolve_app_dir() {
    local candidate

    for candidate in \
        "${APP_DIR:-}" \
        "/var/www/html" \
        "/opt/render/project/src" \
        "$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
    do
        if [ -n "$candidate" ] && [ -f "$candidate/artisan" ]; then
            echo "$candidate"
            return 0
        fi
    done

    echo "$(pwd)"
}

APP_DIR="$(resolve_app_dir)"
cd "$APP_DIR"

source_nix() {
    if [ -f "${HOME}/.nix-profile/etc/profile.d/nix.sh" ]; then
        # shellcheck disable=SC1091
        source "${HOME}/.nix-profile/etc/profile.d/nix.sh"
    fi
}

find_php() {
    source_nix

    if [ -f "$APP_DIR/storage/.php-bin" ]; then
        cached="$(tr -d '\r\n' < "$APP_DIR/storage/.php-bin")"
        if [ -n "$cached" ] && [ -x "$cached" ]; then
            echo "$cached"
            return 0
        fi
    fi

    if [ -x /usr/local/bin/php ]; then
        echo /usr/local/bin/php
        return 0
    fi

    if command -v php >/dev/null 2>&1; then
        command -v php
        return 0
    fi

    local found
    found="$(find /nix/store -path '*/bin/php' -type f -executable 2>/dev/null | head -1 || true)"
    if [ -n "$found" ]; then
        echo "$found"
        return 0
    fi

    for candidate in /nix/store/*php*/bin/php; do
        if [ -x "$candidate" ]; then
            echo "$candidate"
            return 0
        fi
    done

    return 1
}

if ! PHP_BIN="$(find_php)"; then
    echo "ERROR: PHP not found."
    echo ""
    echo "Render is using Node runtime. Laravel needs Docker on Render."
    echo "Fix: Settings -> Environment -> Docker"
    echo "     Dockerfile Path -> ./Dockerfile"
    echo "     Start Command -> leave EMPTY"
    echo "     Build Command -> leave EMPTY"
    echo ""
    exit 1
fi

echo "Using PHP: $PHP_BIN"
echo "App directory: $APP_DIR"

ensure_sqlite_database() {
    if [ "${DB_CONNECTION:-sqlite}" != "sqlite" ]; then
        return 0
    fi

    mkdir -p database
    touch database/database.sqlite
    chmod 664 database/database.sqlite
    echo "SQLite database ready."
}

ensure_sqlite_database

wait_for_database() {
    if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
        return 0
    fi

    if [ -z "${DB_URL:-}" ] && [ -z "${DB_HOST:-}" ]; then
        return 0
    fi

    echo "Waiting for database..."

    for _ in $(seq 1 45); do
        if "$PHP_BIN" artisan db:show >/dev/null 2>&1; then
            echo "Database is ready."
            return 0
        fi

        sleep 2
    done

    echo "Database connection timed out."
    exit 1
}

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
