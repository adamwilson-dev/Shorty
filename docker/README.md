## Docker Container

These files can be used to quickly start a local development/test copy using Docker. When you start the container, Docker will download all the dependencies and configure the environment.

### Usage

- Download and install Docker ([Docker Desktop](https://www.docker.com/products/docker-desktop/))
- Open CMD prompt and execute the commands below:

```
$ cd docker
$ docker-compose build
$ docker-compose up
```

- Run Composer install:
```
$ docker exec -it php83_apache_server composer install
```
- Run the DB migration:
```
$ docker exec -it php83_apache_server php artisan migrate
```
- Access the application via a web browser on "https://localhost:4431/" (accept any certificate warnings in the browser)

### Database

The database is persisted after first load. To purge the database, remove the "shorty_dbdata" image within Docker.

### Useful Commands

Interact with the container using the "docker exec" command. Example below:

```
$ docker exec -it php83_apache_server php artisan migrate
$ docker exec -it php83_apache_server composer install
```

### Resources

- https://github.com/adamwilson-dev/Shorty
