# laravel-vue-groupware
Vue.js SPA for groupware

* Vue 2.6
* PHP 7.2
* Laravel 7
* Nginx
* MySQL

* Vuetify, Vuex, Vue router

* Laradock


#### Composer
```
composer install
```

#### Set .env
```
cp .env.example .env
php artisan key:generate

APP_ENV=local
APP_URL=http://groupware.local
...

ADMIN_EMAIL=XXX@XXX.com
ADMIN_PASSWORD=XXXXXXX

DB_HOST=XXX.XXX.XXX.XXX
DB_PORT=3306
DB_DATABASE=XXX
DB_USERNAME=XXX
DB_PASSWORD=XXX
...
MAIL_MAILER=XXX
MAIL_HOST=XXX
MAIL_PORT=XXX
MAIL_USERNAME=xxx@bglen.jp
MAIL_PASSWORD=XXX
MAIL_FROM_ADDRESS=xxx@bglen.jp
MAIL_FROM_NAME=XXX
...

FILESYSTEM_DRIVER=(local/public/s3)
AWS_ACCESS_KEY_ID=xxx
AWS_SECRET_ACCESS_KEY=xxx
AWS_DEFAULT_REGION=xx
AWS_BUCKET=xxx
AWS_URL=xxx

配信機能で、Gmailの安全性調整が必要そうです。
```

#### Docker(laradock)
```
// Start service
cd laradock
docker-compose up -d nginx mysql workspace

// Stop service
docker-compose down
```

#### Set DB
```
cd laradock
// For dev (with test datas)
docker-compose run workspace php artisan migrate:refresh --seed
docker-compose run workspace php artisan passport:install
copy PERSONAL_Client_ID1, PERSONAL_Client_Secret1, PERSONAL_Client_ID2, PERSONAL_Client_Secret2 to .env
```

#### Build Vue
```
cd frontend
npm install

// Use vue cli serve
npm run serve
(http://localhost.8080)

// Use docker
npm run build
(http://groupware.local/) need to set host (127.0.0.1  groupware.local)
```

#### Deploying Passport(prod only)
```
php artisan passport:keys
```