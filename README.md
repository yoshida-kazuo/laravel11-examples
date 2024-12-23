# laravel11-examples
Laravel 11 Sample Code

## 初期設定
開発環境に合わせてdocker環境変数を設定
```sh
# .envを編集
# APP_GROUP: gid名
# APP_GROUP_ID: gid
# APP_USER: uid名
# APP_USER_ID: uid
# APP_NETWORK: docker network list [NAME]
cp .env-example .env
```
ネットワーク作成サンプル
```sh
docker network create \
  --driver bridge \
  --subnet 10.255.0.0/16 \
  --gateway 10.255.0.1 \
  custom-networks
```
ネットワーク確認
```sh
docker network list
```

## インストール
```sh
docker compose build backend
docker compose build frontend
docker compose run --rm frontend npm install
docker compose up -d backend
docker compose exec backend php artisan key:generate
# src/.env 適宜設定しmigration実行
docker compose exec backend php artisan migrate --path=database/migrations/initialize
docker compose exec backend php artisan db:seed --class=InitializeSeeder
docker compose exec backend php artisan user:create <email> <password>
docker compose up -d frontend
```
