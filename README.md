# Smile POS (レジアプリケーション)

## 概要
個人の飲食店などでの利用を想定した、シンプルで直感的な操作が可能なレジ管理システムです。

## 環境構築手順

### 1. リポジトリのクローンと環境変数の準備
```bash
git clone [https://github.com/hayato0823/portfolio.git](https://github.com/hayato0823/portfolio.git)
cd portfolio/register-app/smile-kitchen
cp .env.example .env
docker compose up -d
# PHPコンテナに入る
docker compose exec app-sdt bash

# --- ここからはコンテナ内での操作になります ---
cd smile-kitchen

# PHPパッケージのインストール
composer install

# フロントエンドのパッケージインストールとビルド
npm install
npm run build

# アプリケーションキーの生成
php artisan key:generate

# データベースのテーブル作成
php artisan migrate
使用方法
1. アカウント作成
最初に http://localhost:8080/register にアクセスし、新規ユーザー登録を行ってください。

2. レジの準備（レジ金登録）
登録・ログイン後、画面の「ハンバーガーボタン（メニュー）」を開き、「ホーム」へ移動します。

営業開始のための「レジ金（釣り銭の準備金）」を入力して登録します。

3. 販売商品の登録
メニューから「商品登録」画面へ進み、販売する商品（商品名や価格など）を追加してください。

4. 会計業務
商品の登録が終わったら「会計業務」へ進みます。

先ほど登録した商品を実際にカートに入れ、お会計の流れを体験いただけます。
