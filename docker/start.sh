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
    exit 1
fi

echo "Using PHP: $PHP_BIN"
echo "App directory: $APP_DIR"

prepare_sqlite() {
    if [ "${DB_CONNECTION:-sqlite}" != "sqlite" ]; then
        return 0
    fi

    unset DB_URL
    export DB_DATABASE="${DB_DATABASE:-$APP_DIR/database/database.sqlite}"

    mkdir -p database storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
    touch "$DB_DATABASE"
    chmod -R 775 storage bootstrap/cache database
    chmod 664 "$DB_DATABASE"
    echo "SQLite database ready at $DB_DATABASE"
}

app_key_works() {
    "$PHP_BIN" -r "
        require 'vendor/autoload.php';
        \$app = require 'bootstrap/app.php';
        \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        Illuminate\Support\Facades\Crypt::encryptString('render-health-check');
    " >/dev/null 2>&1
}

ensure_app_key() {
    if app_key_works; then
        echo "APP_KEY is valid."
        return 0
    fi

    export APP_KEY="$("$PHP_BIN" artisan key:generate --show)"
    echo "Generated APP_KEY for this deploy."
    echo "Add this to Render Environment to keep it stable:"
    echo "$APP_KEY"
}

prepare_sqlite
"$PHP_BIN" artisan config:clear
ensure_app_key

"$PHP_BIN" artisan migrate --force

if [ "${SEED_DATABASE:-false}" = "true" ]; then
    "$PHP_BIN" artisan db:seed --force || echo "Seed skipped (database may already contain data)."
fi

"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache

echo "Starting server on port ${PORT:-8080}..."
exec "$PHP_BIN" artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
