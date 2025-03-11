## The Problem

At [Hyvor Talk](https://talk.hyvor.com), where we use PDO to connect to MYSQL, we occationally get "Too many connections" error when we have a traffic spike. In other languages like Go, this can be easily solved by using a connection pool. But, since PHP is stateless (each HTTP request creates a new PHP process), we can't use a connection pool. The closest thing we can use is [PHP persistent connections](https://www.php.net/manual/en/features.persistent-connections.php#95340). This is an experiment to see if we can use this feature to solve the problem.

Main tools:

- PHP 8.4 with FPM (Experiment 1)
- PHP 8.4 with FrankenPHP (Experiment 2 TODO)
- MySQL 8.0.26

Monitoring tools:

- Prometheus
- Grafana

See notes.md
