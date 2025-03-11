Run `docker compose up -d`
Go to `http://localhost:8400` to see PHP-fpm is running
Go to `http://localhost:3000/` for Grafana and log in with `admin` and `admin`
Go to `http://localhost:9090/` for Prometheus
Add Prometheus as a data source in Grafana
Imported this dashboard for MYSQL: https://grafana.com/grafana/dashboards/14057-mysql/

Dashboard:
![Dashboard](https://raw.githubusercontent.com/supun-io/php-pdo-persistent/refs/heads/main/assets/screenshot-grafana-mysql.png)

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

This resulted in MYSQL too many connections and actually metrics not working due to not being able to connect to the database.

#### 2. With persistent connection

```
wrk -t12 -c400 -d30s http://localhost:8400?persistent=true
```

Still got the too many connections issue while trying to visit the URL while the test was running.

Just to make sure it is not a metrics issue, I ran it with 100 connections.

```
wrk -t12 -c100 -d60s http://localhost:8400?persistent=true
```

It nicely showed the metrics in Grafana.

![Metrics](https://raw.githubusercontent.com/supun-io/php-pdo-persistent/refs/heads/main/assets/screenshot-mysql-100.png)

#### 3. With persistent connection and max limit

Now, the most important part is to check if PHP can limit the max connections to a given number. I will set it to 10 in php.ini.

```
# php.ini
mysqli.max_persistent = 10
```

No change. It still ran up to 100 connections.

Ok, so I learned that there are two extensions to connect to MYSQL: mysqli and PDO. Previously I though PDO uses mysqli under the hood, but it does not look like it.

I could not find a max connection setting in PDO. I will try mysqli setting instead.

Then, I found mysqli extension actually throws an error if the max connections are reached (https://github.com/php/php-src/blob/33c4ca36e43cf03d7aa8eccf4493d84a6a5714eb/ext/mysqli/mysqli_nonapi.c#L226), which is not how a connection pool should work.
