## Shorty Short Url Laravel Script

Work in progress. These docs will look better soon, honestly...!

## Getting Started

@todo - soon

## Usage

@todo - soon

### Usage With Docker

See the Docker [README](./docker/README.md) for guidance on creating a local instance using Docker.

### Useful Commands

Remember that if you're using Docker, precede the commands with "docker exec -it php83_apache_server".

Create database migration file:
```
$ php artisan make:migration create_urls_table --create=urls
```

Apply database changes:
```
$ php artisan migrate
```

Run Vite:
```
$ npm install && npm run dev
```

Build NPM for production:
```
$ npm install && npm run build
```

Run Unit Tests:
```
$ php artisan test
```
### License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE.md) file for details.

### Credits

- [Laravel Framework](https://laravel.com/)
- [Breeze & Blade](https://github.com/laravel/breeze)
