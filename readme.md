## Beatsaver

- Beatsaver is a custom song platform for Beatsaber (http://beatsaber.com/)
- In order to use custom song see [BeatSaberModInstaller](https://github.com/Umbranoxio/BeatSaberModInstaller/releases)

## Wiki
For more infomation visit [https://github.com/Byorun/beatsaver-laravel/wiki](https://github.com/Byorun/beatsaver-laravel/wiki)

## Setup instructions

### Vagrant

Base requirements:
* Composer (and therefore php)
* Vagrant
* Vagrant backend provider like Hyper-V, Parallels, VMWare or VirtualBox

```
cp Homestead.yaml.example Homestead.yaml
```

Now have a look at the `Homestead.yaml` file, if you choose a backend other then VirtualBox please also change the `provider` line in the config.

Setup the configuration file:
```
cp .env.example .env
```
Don't forget to configure the email driver. You can set `MAIL_DRIVER=log` during development.

Now run:
```
composer install
vagrant up
```

Visit the development site:

http://192.168.10.10

You can now register a user.

If you get stuck check the offical homestead docs from laravel https://laravel.com/docs/5.6/homestead

### Standalone

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
php artisan serve --port=8080
```

Visit the development site:

http://localhost:8080

You can now register a user.

## License

Beatsaver is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
