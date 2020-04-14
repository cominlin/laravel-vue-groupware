# laravel-vue-groupware
Vue.js SPA for groupware

* Vue 2.6
* PHP 7.2
* Laravel 7
* Nginx
* MySQL

* Vuetify, Vuex, Vue router

* Laradock


####Composer
```
composer install
```

####Build Vue
```
npm install
ls

// For dev
npm run dev

// For prod
npm run prod
```

####Set .env
```
cp .env.example .env
php artisan key:generate

APP_ENV=local
APP_URL=http://xxx.com
...
DB_HOST=XXX.XXX.XXX.XXX
DB_PORT=3306
DB_DATABASE=XXX
DB_USERNAME=XXX
DB_PASSWORD=XXX
...
MAIL_USERNAME=xxx@bglen.jp
MAIL_PASSWORD=xxxxxx
MAIL_FROM_ADDRESS=xxx@bglen.jp
MAIL_FROM_NAME=xxx
...
ADMIN_EMAIL=XXX@XXX.com
ADMIN_PASSWORD=XXXXXXX

FILESYSTEM_DRIVER=(local/public/s3)
AWS_ACCESS_KEY_ID=xxx
AWS_SECRET_ACCESS_KEY=xxx
AWS_DEFAULT_REGION=xx
AWS_BUCKET=xxx
AWS_URL=xxx

配信機能で、Gmailの安全性調整が必要そうです。
```

####Set DB
```
// For dev (with test datas)
php artisan migrate:refresh --seed
php artisan passport:install
copy PERSONAL_Client_ID1, PERSONAL_Client_Secret1, PERSONAL_Client_ID2, PERSONAL_Client_Secret2 to .env

// For prod (with admin and category data only)
php artisan migrate
php artisan db:seed --class=ProductionDataSeeder
```

####Deploying Passport(prod only)
```
php artisan passport:keys
```
