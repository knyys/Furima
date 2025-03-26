# フリマアプリ

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:knyys/Furima.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

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
  
**メール認証のテスト方法(mailtrap)**  
[Mailtrap](https://mailtrap.io)にアクセスしてサインアップ  

1. メールボックスの作成**
Sandboxにある`Setup Inbox`をクリック
- `Add Project`からProject Nameを入力して`Add`をクリック
- Add Inboxをクリックしてインボックスを作成

2. laravelでの設定
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

**決済方法(stripe)**  


## 使用技術(実行環境)
- PHP8.2.28
- Laravel8.83.29
- MySQL8.0.26




## ER図
![hurima](https://github.com/user-attachments/assets/98f90e6f-3640-46eb-a46b-5cce0c2e6d4a)

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/


