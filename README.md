# LEMP stack 

## sample_app フォルダ
この中のファイルが、php, nginx のサービスにマウントされる。`sample_app` というフォルダの名前は任意のものに変えていい。ただ、docker-compose.yml など、`sample-app` という名前前提になっているので、各種ファイルに対して、名前を変更しないと行けない。

## `.env`
データベースのパスワードや、環境固有の設定を行うための、環境変数を定義する。(.env.sample はサンプルなので、これをコピーして`.env`ファイルを作る）

## Document Root
* sample_app フォルダが、nginx と php のサービスにマウントされる。
* nginx がリクエストされたファイルを探す場所は `.docker/nginx/conf.d/php.conf` に書かれている `root` ディレクティブが指定しているパス。
* sample_app の構成に合わせて、`root` ディレクティブのパスを書き換える必要がある。
  * `root` ディレクティブのデフォルト `/var/www/php`。
  * 例えば、`sample_app/public` 配下のファイルをサーブしたいとき、`root`　ディレクティブには、`/var/www/php/public` と書き換える。

## MySQL のマイグレーション
`.docker/mysql/docker-entrypoint-initdb.d` 配下の sql ファイルが `docker-comose up` で mysql サービスが起動するときに、実行される。
これを利用して、初期データのマイグレーションが行える。