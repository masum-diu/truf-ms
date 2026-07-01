#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

if [ -f "${HOME}/.nix-profile/etc/profile.d/nix.sh" ]; then
    # shellcheck disable=SC1091
    source "${HOME}/.nix-profile/etc/profile.d/nix.sh"
fi

find_php() {
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

    return 1
}

mkdir -p storage

if PHP_BIN="$(find_php)"; then
    echo "$PHP_BIN" > storage/.php-bin
    echo "Saved PHP path for runtime: $PHP_BIN"

    if command -v composer >/dev/null 2>&1; then
        composer install --no-dev --optimize-autoloader --no-interaction
    elif [ -f composer.phar ]; then
        "$PHP_BIN" composer.phar install --no-dev --optimize-autoloader --no-interaction
    else
        echo "ERROR: Composer not found during build."
        exit 1
    fi
else
    echo "WARNING: PHP not found during build. Composer install skipped."
fi

npm ci
npx vite build
