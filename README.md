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

## トラブルシューティング
### unhealthy のエラー
```
ERROR: for php  Container "87597405450f" is unhealthy.

ERROR: for phpmyadmin  Container "87597405450f" is unhealthy.
```
* **エラー・事象の内容**
  * mysql サービスに依存しているサービスが `unhealty` のエラーを起こして失敗する
    * mysql に依存しているサービスが起動する条件として、mysql の正常起動を条件としているため(docker-copose.yml の `depends_on` の仕組みによるもの)
* **原因**
  * mysql の起動がうまく行っていない
    * docker-compose.yml の mysql の `volumes` で指定されているパスが相対パスのため
        ```
        volumes:
            - ./.docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
        ```
    * 下記の様に、ホスト側も、コンテナ側も絶対パスであればうまくいく
        ```
        volumes:
            - /home/path/to/the/directory/.docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf

        # 環境変数を使う例。環境変数は `.env` ファイルで定義する。
        volumes:
          - ${MY_CNF}:/etc/mysql/conf.d/my.cnf
        ```
  * なぜ相対パスが使えないのかはわからない（docker-compose の仕様かバグ？)