## Docker Container

These files can be used to quickly start a local development/test copy using Docker. When you start the container, Docker will download all the dependencies and configure the environment.

### Usage

- Download and install Docker ([Docker Desktop](https://www.docker.com/products/docker-desktop/))
- Open CMD prompt and execute the commands below:

```
$ cd docker
$ docker-compose up -d
```

- Access the application via a web browser on "https://localhost"

### Optional File Server

You can also test a 'direct' external file server using Docker. This Docker container only has PHP and the web server, so no database. Guidance below:

- First start the main site, as detailed above
- Open CMD prompt and execute the commands below:

```
$ cd docker/PHP8.3/
$ docker-compose -f file-server-docker-compose.yml build
$ docker-compose -f file-server-docker-compose.yml up
```

- Once complete, access the file server via a web browser on "http://localhost:81" (it will redirect to "http://localhost" when you click around)
- Login to the main site on http://localhost/admin, go to "Server Manage" and add the file server details (http://localhost/admin/server_add_edit)
  - Server Label:
    - Direct Server
  - Server Type:
    - External Server (Direct)
  - File Server Domain Name:
    - localhost:81
  - Server Path To Install:
    - /var/www/html
- Set the "Local Default" storage as status "Read Only" (http://localhost/admin/server_add_edit/1)
- All uploads should now be send to the 'direct' file server. You can use the browser developer tools to monitor the network traffic to check

Note: This will map the main site and file server to the same codebase. Ideally make a copy of the codebase before starting the file server to ensure any file system changes don't overlap

### Important

- None of the data or files are persisted. Each time you start the Docker container a new copy of the database is created. It's then destroyed when you stop the container
- This should not be used as a replacement for installing on a web host. This purely exists for the purposes of local development/testing
- Crons do not run on the Docker containers. You can run these manually via the terminal if needed

### Resources

- www.yetishare.com