## Beatsaver 

- Beatsaver is a custom song platform for Beatsaber (http://beatsaber.com/)
- In order to use custom song see [BeatSaberModInstaller](https://github.com/Umbranoxio/BeatSaberModInstaller/releases)

## Wiki
For more infomation visit [https://github.com/Byorun/beatsaver-laravel/wiki](https://github.com/Byorun/beatsaver-laravel/wiki)

## Setup instructions

Base requirements:

* PHP >=7.1
* MariaDB >=10.2

Install dependencies:

```
composer install
```

Setup the configuration file:

```
cp .env.example .env
```

Configure the database by editing `.env`

Don't forget to configure the email driver. You can set `MAIL_DRIVER=log` during development.

Run setup and database migrations:

```
php artisan key:generate
php artisan migrate
```

Start:

```
php -S localhost:8080 -t public
```

Visit the development site:

http://localhost:8080

You can now register a user.

## License

Beatsaver is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
