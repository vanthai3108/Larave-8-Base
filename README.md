# Laravel app
### Run project

```
make up
make php
composer install
cp .env.example .env
make migrate
php artisan optimize:clear
make seed
```

Rebuild Docker
```sh
make down
make build
make up
```

### Laravel IDE Helper
https://github.com/barryvdh/laravel-ide-helper

```
php artisan ide-helper:generate
```
