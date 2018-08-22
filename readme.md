## Beatsaver 

- Beatsaver is a custom song platform for Beatsaber (http://beatsaber.com/)
- In order to use custom song see [BeatSaberModInstaller](https://github.com/Umbranoxio/BeatSaberModInstaller/releases)

## Wiki
For more infomation visit [https://github.com/Byorun/beatsaver-laravel/wiki](https://github.com/Byorun/beatsaver-laravel/wiki)

## Setup instructions

### Docker

Base requirements:

* Docker
* Docker Compose

Setup the configuration file:

```bash
cp .env.example .env
```

Install dependencies:

```
Windows: docker run --rm --interactive --tty --volume %CD%:/app composer install
Linux: docker run --rm --interactive --tty --volume $PWD:/app composer install
```

Start the application stack:

```bash
docker-compose up -d
```

Generate secure key, run migrations, and link storage folder:

```bash
docker-compose exec php-fpm php artisan key:generate
docker-compose exec php-fpm php artisan migrate
docker-compose exec php-fpm php artisan storage:link
```

### Manual

Base requirements:

* PHP >=7.1
* MariaDB >=10.2

Install dependencies:

```bash
composer install
```

Setup the configuration file:

```bash
cp .env.example .env
```

Configure the database by editing `.env`

Don't forget to configure the email driver. You can set `MAIL_DRIVER=log` during development.

Run setup and database migrations:

```bash
php artisan key:generate
php artisan migrate
```

Start:

```bash
php artisan serve --port=8080
```

Visit the development site:

http://localhost:8080

You can now register a user.

## License

Beatsaver is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
