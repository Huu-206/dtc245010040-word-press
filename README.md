# WordPress DevOps Lab

Local WordPress stack with MySQL, phpMyAdmin, Nginx reverse proxy, Prometheus, Grafana, cAdvisor, exporters, Loki, and Promtail.

## Requirements

- Docker Desktop with Docker Compose v2
- Ports 80, 443, and 3000 available on localhost
- Docker available to generate the local self-signed TLS certificate

## Start

1. Keep `.env` local. If setting up a fresh checkout, copy `.env.example` to `.env` and replace every placeholder with unique passwords. `APP_ADMIN_USER` and `APP_ADMIN_PASSWORD` are shared by the WordPress administrator, Grafana administrator, and a database-scoped MySQL login for phpMyAdmin.
2. Generate a local self-signed certificate (the certificate and private key stay local and are ignored by Git):

	```powershell
	New-Item -ItemType Directory -Force nginx/ssl
	docker run --rm -v "${PWD}/nginx/ssl:/certs" alpine:3.20 sh -c "apk add --no-cache openssl >/dev/null && openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /certs/nginx-selfsigned.key -out /certs/nginx-selfsigned.crt -subj '/CN=localhost' -addext 'subjectAltName=DNS:localhost,IP:127.0.0.1'"
	```

3. Start the services with `docker compose up -d`.
4. Check service state with `docker compose ps`.
5. Open WordPress at <https://localhost>, phpMyAdmin at <https://localhost/pma/> (or <https://localhost/phpmyadmin/>), and Grafana at <http://localhost:3000>. Accept the browser warning for the locally self-signed certificate.
6. Create or reset the WordPress administrator using the shared credentials:

	```powershell
@'
<?php
require '/var/www/html/wp-load.php';
$username = getenv('APP_ADMIN_USER');
$password = getenv('APP_ADMIN_PASSWORD');
$userId = username_exists($username);
if ($userId) {
	wp_set_password($password, $userId);
} else {
	$userId = wp_create_user($username, $password, 'admin@localhost');
}
if (is_wp_error($userId)) {
	fwrite(STDERR, $userId->get_error_message() . "\n");
	exit(1);
}
(new WP_User($userId))->set_role('administrator');
?>
'@ | docker compose exec -T wordpress php
	```

7. Sign in to Grafana and WordPress with `APP_ADMIN_USER` and `APP_ADMIN_PASSWORD` from `.env`. In phpMyAdmin, use the same credentials to access the WordPress database. Open the **Monitoring** folder and the **WordPress Stack Overview** dashboard in Grafana.

## Product website

The `Nem Xứ Thanh` theme is mounted read-only from `wordpress/themes/nem-xu-thanh`. It is active in this local WordPress database; on a fresh install, activate it under **Appearance → Themes**. Product buttons scroll to the request form, which stores pending inquiries under **wp-admin → Đơn nem**. The form does not charge or process payment.

The sample pack sizes and prices are placeholders. Replace them in `nem_xu_thanh_products()` in the theme's `functions.php` and update the product story, ingredients, shelf life, handling guidance, contact details, and delivery terms with information verified by the actual producer before accepting real orders. The hero photo is by Ron Diggity under CC BY 2.0; its attribution and license are linked in the page footer.

Prometheus and Loki are reachable by Grafana and exporters only on the Docker monitoring network; their ports are not published on the host. MySQL is on an internal-only network. The web and Grafana host ports are bound to localhost.

## Logs in Grafana Explore

Select the Loki data source and run:

```logql
{container="wp_app"}
{container=~"wp_.*"} |= "error"
rate({container="wp_nginx"}[1m])
{job="nginx_access"}
{job="nginx_access"} |~ "status=[45][0-9]{2}"
{job="nginx_access"} |= "/wp-admin"
```

## Stop

Run `docker compose down`. Persistent WordPress, MySQL, Prometheus, Grafana, Loki, and Nginx access log data remains in named volumes. To remove that data too, run `docker compose down -v`.

## Security notes

- Never commit `.env`; use `.env.example` as the tracked template.
- Change all example passwords before deployment and rotate any credentials that were previously committed.
- This is a local lab, not an internet-facing deployment. Promtail needs access to the Docker socket to discover container logs; a read-only socket mount does not restrict Docker API permissions. Do not expose this host or socket to untrusted users.
- cAdvisor requires read-only host filesystem mounts to collect container metrics.