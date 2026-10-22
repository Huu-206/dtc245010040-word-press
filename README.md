# WordPress DevOps Lab

Local WordPress stack with MySQL, phpMyAdmin, Nginx reverse proxy, Prometheus, Grafana, cAdvisor, exporters, Loki, and Promtail.

## Requirements

- Docker Desktop with Docker Compose v2
- Ports 80 and 3000 available on localhost

## Start

1. Keep `.env` local. If setting up a fresh checkout, copy `.env.example` to `.env` and replace every placeholder with unique passwords.
2. Start the services with `docker compose up -d`.
3. Check service state with `docker compose ps`.
4. Open WordPress at <http://localhost>, phpMyAdmin at <http://localhost/phpmyadmin/>, and Grafana at <http://localhost:3000>.
5. Sign in to Grafana with `GRAFANA_ADMIN_USER` and `GRAFANA_ADMIN_PASSWORD` from `.env`. The provisioned dashboard is named **WordPress DevOps Lab**.

Prometheus and Loki are reachable by Grafana and exporters only on the Docker monitoring network; their ports are not published on the host. MySQL is on an internal-only network. The web and Grafana host ports are bound to localhost.

## Logs in Grafana Explore

Select the Loki data source and run:

```logql
{container="wp_app"}
{container=~"wp_.*"} |= "error"
rate({container="wp_nginx"}[1m])
```

## Stop

Run `docker compose down`. Persistent WordPress, MySQL, Prometheus, and Grafana data remains in named volumes. To remove that data too, run `docker compose down -v`.

## Security notes

- Never commit `.env`; use `.env.example` as the tracked template.
- Change all example passwords before deployment and rotate any credentials that were previously committed.
- This is a local lab, not an internet-facing deployment. Promtail needs access to the Docker socket to discover container logs; a read-only socket mount does not restrict Docker API permissions. Do not expose this host or socket to untrusted users.
- cAdvisor requires read-only host filesystem mounts to collect container metrics.