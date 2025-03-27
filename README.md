Развертка
1. composer install
2. используется sail
2. ./vendor/bin/sail up -d
3. ./vendor/bin/sail artisan key:generate
4. ./vendor/bin/sail artisan migrate
5. в env.example в конце добавил ключи для dadata, необходимо будет вставить для теста в .env

Пара слов о проекте
1. код оформлял чисто, разделял ответственности, но и не усложнял без необходимости
2. также как в тз оформил swagger, /api/documentation показаны все методы
3. добавил feature тесты. запустить: artisan test
