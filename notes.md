Run `docker compose up -d`
Go to `http://localhost:8400` to see PHP-fpm is running
Go to `http://localhost:3000/` for Grafana and log in with `admin` and `admin`
Go to `http://localhost:9090/` for Prometheus
Add Prometheus as a data source in Grafana
Imported this dashboard for MYSQL: https://grafana.com/grafana/dashboards/14057-mysql/

Dashboard:
![Dashboard](../assets/screenshot-grafana-mysql.png)

### Setting up the database

```bash
docker exec -it mysql bash

mysql -p
# password: mysql

CREATE DATABASE test;
USE test;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

INSERT INTO users (name, email) VALUES ('John Doe', 'john@hyvor.com');
INSERT INTO users (name, email) VALUES ('Jane Doe', 'jane@hyvor.com');
```

See fpm/index.php for the PHP code to connect to the database. You should see the users in the browser.

### Load testing

Use WRK: https://github.com/wg/wrk

#### 1. Without persistent connection

```
wrk -t12 -c400 -d30s http://localhost:8400
```
