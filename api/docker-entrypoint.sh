#!/bin/sh
set -e

ephemeris_path="${SWEPH_PATH:-/var/www/api/swetest/sweph}"
swetest_path="${SWETEST_PATH:-/var/www/api/swetest/src}"
checksum_file="${ephemeris_path}/ephemeris.sha256"

if [ ! -f "$checksum_file" ]; then
    echo "FATAL: Swiss Ephemeris checksum manifest is missing: $checksum_file" >&2
    exit 70
fi

if ! (cd "$ephemeris_path" && sha256sum --check ephemeris.sha256); then
    echo "FATAL: Swiss Ephemeris data files are missing or failed SHA-256 verification in $ephemeris_path" >&2
    exit 70
fi

if ! ephemeris_probe=$("$swetest_path/swetest" -edir"$ephemeris_path" -eswe -b01.01.2000 -ut00:00:00 -p01 -fPl -g, -head 2>&1); then
    echo "FATAL: Swiss Ephemeris startup probe failed: $ephemeris_probe" >&2
    exit 70
fi

case "$ephemeris_probe" in
    *"using Moshier"*)
        echo "FATAL: Swiss Ephemeris startup probe attempted a Moshier fallback: $ephemeris_probe" >&2
        exit 70
        ;;
esac

APP_ENV=prod php bin/console cache:warmup --no-optional-warmers 2>/dev/null || \
    APP_ENV=prod php bin/console cache:clear --no-warmup 2>/dev/null || true

php-fpm -D

exec nginx -g 'daemon off;'
