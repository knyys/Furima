# フリマアプリ

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:knyys/Furima.git`
2. `cd Furima`
3. DockerDesktopアプリを立ち上げる
4. `docker-compose up -d --build`

> *MySQLは、OSによって起動しない場合があるので、それぞれのPCに合わせてdocker-compose.ymlファイルを編集してください*  
  
**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成
``` bash
php artisan key:generate
```
6. マイグレーションの実行
``` bash
php artisan migrate
```
7. シーディングの実行
``` bash
php artisan db:seedphp artisan storage:link
```
8. シンボリックリンク作成
``` bash
php artisan storage:link
```
  
**メール認証の設定(mailtrap)**  
[Mailtrap](https://mailtrap.io)にアクセスしてサインアップ  

- Integrationsをクリックして`Laravel 7+`
- Laravelの`.env`用の認証情報が表示されるのでコピーし`.env`ファイルに貼り付け
- Mailtrapの認証情報をコピーし`.env`ファイルに貼り付け

  ```vim
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=生成されたUSERNAME  //mailtrapを貼り付け
  MAIL_PASSWORD=生成されたPASSWORD  //mailtrapを貼り付け
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS="hello@example.com"
  MAIL_FROM_NAME="${APP_NAME}"
  ```
- メールを送るとMailtrapのインボックス内に表示される

**ngrokとstripeの設定（決済サービス）**  
1. ngrokの設定  
  - [ngrok](https://ngrok.com/)にアクセスしてサインアップ  
  - Dockerを選び、インストール
    ``` bash
    docker pull ngrok/ngrok
    ```   
  - アプリをオンラインで展開する（Authtokenを確認し、YOUR Authtokenに代入する）
    ``` bash
    docker run --net=host -it -e NGROK_AUTHTOKEN=YOUR Authtoken ngrok/ngrok:latest http 80
    ``` 
  - ngrok が以下のようなURLを発行  
        `Forwarding                    https://2af2-222-150-156-21.ngrok-free.app -> http://localhost:80`  
  - `.env`ファイルの APP_URL をngrok のURLに変更  
    ``` bash
    APP_URL=APP_URL=your_ngrok_url
    ```
  - 設定を反映させる
    ``` text
    php artisan config:clear  
    php artisan cache:clear  
    ```
2. stripeの設定  
- [stripe](https://dashboard.stripe.com/register)にアクセスしてサインアップ
- アカウント作成後、Stripeダッシュボードにログイン
- ダッシュボードの右上にあるメニューから、「Developers」＞「API keys」を選択し、テスト用のAPIキー（Publishable KeyとSecret Key）をメモ  
- ``` text
  docker-compose exec php bash  
  composer require stripe/stripe-php
  ```
- Stripe APIキーの設定  
`.env`ファイルにStripeのAPIキーを追加
  ``` text
  STRIPE_SECRET_KEY=your_secret_key  
  STRIPE_PUBLISHABLE_KEY=your_publishable_key
  ```
- config/services.php に設定を追加
  ``` text  
  'stripe' => [
      'secret' => env('STRIPE_SECRET_KEY'),
      'publishable' => env('STRIPE_PUBLISHABLE_KEY'),  
  ],
  ```
- Webhookエンドポイントの作成
  「Developers」＞「Webhook」を選択し、「新規Webhookの作成」
  イベントには「checkout.session.completed」を選択
  エンドポイントはngrokで取得したURLを使用「your_ngrok_url/stripe/webhook」  
- 作成後、送信先の詳細から署名シークレットをコピーし、`.env`ファイルに記述
  ``` text
  STRIPE_WEBHOOK_SECRET=your_webhook_secret  
  ```
- 設定を反映させる
  ``` text
  php artisan config:clear  
  php artisan cache:clear  
  ```
- Stripeから送られるイベントを設定したWebhookエンドポイントに転送できるようにする
  ``` text
  stripe listen --forward-to your_ngrok_url/stripe/webhook  
  ```  




## 使用技術(実行環境)
- PHP8.2.28
- Laravel8.83.29
- MySQL8.0.26




## ER図
![hurima](https://github.com/user-attachments/assets/98f90e6f-3640-46eb-a46b-5cce0c2e6d4a)

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/


