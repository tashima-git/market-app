# フリマアプリ

## 環境構築

### Docker ビルド

1. リポジトリをクローン
```bash
git clone git@github.com:tashima-git/market-app.git
```

2. Docker Desktop アプリを起動

3. Docker コンテナをビルドして起動
```bash
docker-compose up -d --build
```

### Laravel 環境構築

1. PHP コンテナに入る
```bash
docker-compose exec php bash
```

2. Composer で依存パッケージをインストール
```bash
composer install
```

3. `.env.example` をコピーして `.env` を作成
```bash
cp .env.example .env
```

4. `.env` に以下の DB 環境変数を設定
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキーを作成
```bash
php artisan key:generate
```

6. マイグレーションを実行
```bash
php artisan migrate
```

7. シーディングを実行
```bash
php artisan db:seed
```

## 使用技術・実行環境

- PHP 8.4.8
- Laravel 10.49.0
- MySQL 8.0.26

## ER図

- `alt` 形式で作成予定（ER図画像やリンクを追加する）

## URL

- 開発環境: [http://localhost/](http://localhost/)
- phpMyAdmin: [http://localhost:8080/](http://localhost:8080/)
