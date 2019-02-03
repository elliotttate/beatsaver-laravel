## BeatSaver

- BeatSaver is a custom song platform for Beat Saber (https://beatgames.com/)
- In order to use custom songs see [ModSaber Installer](https://github.com/lolPants/modsaber-installer/releases)

## Wiki
For more infomation visit [https://github.com/elliotttate/beatsaver-laravel/wiki](https://github.com/elliotttate/beatsaver-laravel/wiki)

## Setup instructions

### Vagrant

Base requirements:
* PHP (recommended version 7.x)
* [Composer](https://getcomposer.org/)
* [Vagrant](https://www.vagrantup.com/)
* Vagrant backend provider like [VirtualBox](https://www.virtualbox.org/), Hyper-V, Parallels, VMWare.

```
cp Homestead.yaml.example Homestead.yaml
```

Now have a look at the `Homestead.yaml` file, if you choose a backend other then VirtualBox please also change the `provider` line in the config.

Setup the configuration file:
```
cp .env.example .env
```

Don't forget to configure the email driver. Set `MAIL_DRIVER=log` in `.env` during development. 

Now run:
```
composer install
vagrant up
```

Visit the development site:

http://192.168.10.10

You can now register a user. When creating an account, you'll need to verify your account. With `MAIL_DRIVER=log`, the verification link will be logged. To access these logs and view the verification link you need to visit, you can SSH into the VM and view the logs:

```
vagrant ssh
cat code/storage/logs/laravel.log
```

If you want to test uploads, you could download a map from [Production BeatSaver](https://beatsaver.com/browse/newest).

If you get stuck check the offical homestead docs from laravel https://laravel.com/docs/5.6/homestead

### Standalone

Base requirements:

* PHP >=7.1
* MariaDB >=10.2

Install dependencies:

```
composer install
```

Setup required options in `php.ini`:

```
post_max_size = 16M
upload_max_filesize = 16M
```

Setup the configuration file:

```
cp .env.example .env
```

Configure the database by editing `.env`. Recommended changes for development are:
```
# Use a non-persistent, in-memory cache
CACHE_DRIVER=array

# Log all mail events to storage/logs/laraval.log
# (Needed to view user registration links)
MAIL_DRIVER=log
```

Create the database and user:

```
# Use the configured values in .env to create the user
# These commands can also be run in the database's shell
source .env
echo "CREATE DATABASE \`${DB_DATABASE}\`;"\
     "CREATE USER '${DB_USERNAME}'@localhost IDENTIFIED BY '${DB_PASSWORD}';"\
     "GRANT ALL privileges ON \`${DB_DATABASE}\`.* TO '${DB_USERNAME}'@localhost;FLUSH PRIVILEGES;"\
     | mysql -u root -p
```

Run setup and database migrations:

```
# Generate an application key
php artisan key:generate

# Make stored files accessible from the web
php artisan storage:link

# Run database migrations
php artisan migrate
```

Start:

```
php artisan serve --port=8080
```

Visit the development site:

http://localhost:8080

You can now register a user and start using the application.

## License

BeatSaver is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
