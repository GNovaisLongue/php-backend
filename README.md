# PHP backend + Docker

PHP backend using Docker and MySQL.

## With Docker

Execute `docker compose up --build` to build and start the project.
If you want to stop and remove everything associated to this project from your docker, execute `docker compose down -v --rmi 'all'`

## Without Docker

CD into the `backend` folder then use the following command to install [composer](https://getcomposer.org) dependencies.

```bash
  composer install
```

You can execute the following command to run the project using built-in php web server.

```bash
cd ~/backend
php -S localhost:8000 -t
```

- In order to have the MySQL running locally and connected with the backend, it will be required to run an APACHE server containing the backend files (just get [XAMPP](https://www.apachefriends.org) and configure it). Inside `mysql` folder, there's a dump that can be used to create and populate the table used for this project. Make sure the ports on the frontend, backend and MySQL are matching.

## Localhost

- [http://localhost:8080](http://localhost:8080) for mysql database.
- [http://localhost:8000](http://localhost:8000) for apache server.
