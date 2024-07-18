## Docker Container

These files can be used to quickly start a local development/test copy using Docker. When you start the container, Docker will download all the dependencies and configure the environment.

### Usage

- Download and install Docker ([Docker Desktop](https://www.docker.com/products/docker-desktop/))
- Open CMD prompt and execute the commands below:

```
$ cd docker
$ docker-compose up -d
```

- Access the application via a web browser on "https://localhost:4431/" (accept any certificate warnings in the browser)

### Database

The database is persisted after first load. To purge the database, remove the contents of /docker/dbdata

### Resources

- https://github.com/adamwilson-dev/Shorty