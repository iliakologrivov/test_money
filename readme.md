```
docker-compose up -d
docker-compose exec --user=laradock workspace bash
composer install
./artisan migrate
./artisan db:seed
./artisan user:create --help Создает пользователя
./artisan account:create --help Создаст аккаунт пользователю
./artisan account:list --help Список аккаунтов
./artisan money:send --help Отправить деньги со счета на счет
```