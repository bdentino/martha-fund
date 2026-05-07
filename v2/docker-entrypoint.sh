#!/usr/bin/env bash
#
# Custom entrypoint for the martha-fund v2 WordPress image.
#
# Translates a small set of runtime environment variables into the
# WORDPRESS_* variables that the upstream wordpress:apache image's
# entrypoint already knows how to bake into wp-config.php, then
# delegates to it.
#
# Inputs (env):
#   DATABASE_URL    Full DSN, e.g. mariadb://user:pass@host:3306/dbname
#                   (mysql:// is also accepted). Required.
#   WORDPRESS_BEHIND_PROXY If set (any non-empty value), wp-config.php will
#                   honor X-Forwarded-Proto: https for HTTPS detection.

set -euo pipefail

log() { printf '[martha-entrypoint] %s\n' "$*" >&2; }

# ---------------------------------------------------------------------------
# DATABASE_URL -> WORDPRESS_DB_*
# ---------------------------------------------------------------------------
if [ -z "${DATABASE_URL:-}" ]; then
    log "ERROR: DATABASE_URL is not set."
    log "       Expected e.g. mariadb://user:pass@host:3306/dbname"
    exit 1
fi

urldecode() {
    local data="${1//+/ }"
    printf '%b' "${data//%/\\x}"
}

# Strip scheme (mysql:// or mariadb://; anything else is an error).
case "$DATABASE_URL" in
    mysql://*|mariadb://*)
        _url_no_scheme="${DATABASE_URL#*://}"
        ;;
    *)
        log "ERROR: DATABASE_URL must start with mysql:// or mariadb://"
        exit 1
        ;;
esac

# Split userinfo from host/path.
if [[ "$_url_no_scheme" != *"@"* ]]; then
    log "ERROR: DATABASE_URL is missing user info (expected user:pass@host)."
    exit 1
fi
_userinfo="${_url_no_scheme%%@*}"
_hostpath="${_url_no_scheme#*@}"

# user[:password]
if [[ "$_userinfo" == *":"* ]]; then
    _db_user_raw="${_userinfo%%:*}"
    _db_pass_raw="${_userinfo#*:}"
else
    _db_user_raw="$_userinfo"
    _db_pass_raw=""
fi

# host[:port]/dbname[?query]
_hostport="${_hostpath%%/*}"
_dbpart="${_hostpath#*/}"
if [ "$_dbpart" = "$_hostpath" ]; then
    log "ERROR: DATABASE_URL is missing the database name."
    exit 1
fi
_db_name="${_dbpart%%\?*}"

if [[ "$_hostport" == *":"* ]]; then
    _db_host_only="${_hostport%%:*}"
    _db_port="${_hostport##*:}"
else
    _db_host_only="$_hostport"
    _db_port="3306"
fi

export WORDPRESS_DB_HOST="${_db_host_only}:${_db_port}"
export WORDPRESS_DB_USER="$(urldecode "$_db_user_raw")"
export WORDPRESS_DB_PASSWORD="$(urldecode "$_db_pass_raw")"
export WORDPRESS_DB_NAME="$_db_name"

# ---------------------------------------------------------------------------
# External theme directory
#
# The martha theme is shipped under /opt/martha-themes/martha (outside
# /var/www/html so it isn't shadowed by anonymous volume mounts on
# /var/www/html/wp-content). We append it to $wp_theme_directories from
# wp-config.php; wp-settings.php still registers the default themes
# directory afterwards, so both locations are scanned.
#
# Note: register_theme_directory() lives in wp-includes/theme.php which
# isn't loaded yet when wp-config.php runs, so we set the underlying
# global directly — this is the same array that function appends to.
# ---------------------------------------------------------------------------
read -r -d '' _theme_dir_snippet <<'PHP' || true
if ( ! isset( $wp_theme_directories ) || ! is_array( $wp_theme_directories ) ) {
    $wp_theme_directories = array();
}
$wp_theme_directories[] = '/opt/martha-themes';
PHP
if [ -n "${WORDPRESS_CONFIG_EXTRA:-}" ]; then
    export WORDPRESS_CONFIG_EXTRA="${WORDPRESS_CONFIG_EXTRA}
${_theme_dir_snippet}"
else
    export WORDPRESS_CONFIG_EXTRA="$_theme_dir_snippet"
fi

# ---------------------------------------------------------------------------
# Reverse-proxy HTTPS detection
# ---------------------------------------------------------------------------
if [ -n "${WORDPRESS_BEHIND_PROXY:-}" ]; then
    read -r -d '' _proxy_snippet <<'PHP' || true
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
   $_SERVER['HTTPS']='on';
PHP
    if [ -n "${WORDPRESS_CONFIG_EXTRA:-}" ]; then
        export WORDPRESS_CONFIG_EXTRA="${WORDPRESS_CONFIG_EXTRA}
${_proxy_snippet}"
    else
        export WORDPRESS_CONFIG_EXTRA="$_proxy_snippet"
    fi
fi

# ---------------------------------------------------------------------------
# Hand off to the upstream wordpress entrypoint, which will materialize
# wp-config.php from the WORDPRESS_* env vars and then exec "$@".
# ---------------------------------------------------------------------------
exec docker-entrypoint.sh "$@"
