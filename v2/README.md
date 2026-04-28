# Martha Fund — v2 WordPress Theme Dev Environment

Local Docker-based environment for developing the `martha` theme against the latest stable WordPress and MySQL.

## Stack

| Service     | Image                          | Purpose                              |
| ----------- | ------------------------------ | ------------------------------------ |
| `wordpress` | `wordpress:6.9.4-php8.4-apache` | WordPress core + Apache + PHP 8.4    |
| `db`        | `mysql:8.4.8`                  | MySQL 8.4 LTS                        |
| `wpcli`     | `wordpress:cli-php8.4`         | WP-CLI for scripting (profile `cli`) |
| `phpmyadmin`| `phpmyadmin:5.2`               | DB GUI (profile `tools`)             |

## Layout

```
v2/
├── docker-compose.yml
├── .env.example          # copy to .env and tweak
└── wp-content/
    └── themes/
        └── martha/       # bind-mounted into WordPress; edit live
```

WordPress core, uploads, and plugins live in named Docker volumes so they persist between restarts without polluting the repo.

## Quick start

```bash
cd v2
cp .env.example .env

docker compose up -d
```

Then visit:

- WordPress: http://localhost:8080  (complete the install wizard)
- phpMyAdmin (optional): `docker compose --profile tools up -d phpmyadmin` → http://localhost:8081

After the install wizard, activate the theme at **Appearance → Themes → Martha**, or via WP-CLI:

```bash
docker compose run --rm wpcli theme activate martha
```

## WP-CLI

The `wpcli` service runs under the `cli` profile so it doesn't auto-start.
Pass any WP-CLI command after `run --rm wpcli`:

```bash
docker compose run --rm wpcli plugin list
docker compose run --rm wpcli user create dev dev@example.com --role=administrator --user_pass=dev
docker compose run --rm wpcli search-replace 'http://old.local' 'http://localhost:8080'
```

## Common tasks

```bash
# View logs
docker compose logs -f wordpress

# Restart
docker compose restart wordpress

# Stop everything
docker compose down

# Wipe DB + uploads + plugins (KEEPS your theme code)
docker compose down -v
```

## Notes

- `WP_DEBUG`, `WP_DEBUG_LOG`, and `SCRIPT_DEBUG` are enabled for local development.
- `DISALLOW_FILE_EDIT` is on, so the in-dashboard theme/plugin editor is disabled — edit files locally instead.
- Only the `themes/martha` directory is bind-mounted; uploads and plugins persist in named volumes so installing plugins through the admin UI works as expected.
- Default ports (`8080`, `8081`) and DB credentials can be overridden in `.env`.
