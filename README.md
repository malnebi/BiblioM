# БиблиоМ

БиблиоМ је веб-апликација за управљање књигама, библиотекама и позајмицама. Апликација је изграђена помоћу Laravel-а.

## Локално покретање

Потребни су PHP 8.2+, Composer, Node.js и npm.

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run dev
```

У другом терминалу покренути веб-сервер:

```sh
php artisan serve
```

Примјер конфигурације користи SQLite. За MySQL или други подржани систем, подесити одговарајуће `DB_*` вриједности у `.env` прије покретања миграција.
