# Section 2 その他のWebサーバー環境

## 2-1 Vagrantを使用したCentOS 7環境の起動

サーバーを構築するたびにOSのインストールからやってられないので事前に用意したCentOS 7の環境をVagrantで起動します。

### Vagrantで起動できるCentOS 7のイメージを登録

USBストレージにVagrant用CentOS boxを用意してありますので登録して使用します。

    vagrant box add CentOS7 コピーしたboxファイル --force

### Vagrantの初期設定

作業用ディレクトリを作成し、その中で初期設定を行ないます。

   vagrant init

上記コマンドを実行するとVagrantfileというファイルが作成されます。このファイルにVagrantの設定が書かれています。
そのままではデフォルトのOS(存在しない)を起動してしまうので、CentOS 7を起動するようにします。

viでVagrantfileを開き、

    config.vm.box = "base"

と書かれているのを

    config.vm.box = "CentOS7"

とします。ここで指定するものはvagrant box addで指定したものの名前(上記の例だとCentOS7)を指定します。

### 仮想マシンの起動

    vagrant up

### 仮想マシンの停止

    vagrant halt

### 仮想マシンの一時停止

    vagrant suspend

### 仮想マシンの破棄

最初からやり直したい…そんな時に破棄するとCentOSが初期化されます。
また`vagrant up`をすると立ち上がります…

    vagrant destroy

### 仮想マシンへ接続

実際の仮想マシンへはsshで接続します。

    vagrant ssh

### ホストオンリーアダプターの設定

サーバーを設定したあと、動作確認するために接続するためのIPアドレスを設定します。
また、そのためのNICを追加します。

Vagrantfileの

    Vagrant.configure(2) do |config|

から一番最後の

    end

の間に

    config.vm.network "private_network", ip:"192.168.56.129"

と書くと仮想マシンのNIC2に192.168.56.129のIPアドレスが振られます。
`config.vm.box = "CentOS7"` の下にでも書くといいと思います。

※ 当然のことながら、複数台の仮想マシンを立ち上げる時には異なるIPアドレスを割り当てる必要があります。

### Vagrantfileの反映

Vagrantfileで変更した設定を反映させるには

    vagrant reload

すると反映されます。ただし、再起動されますので注意してね。

## 2-2 Wordpressを動かす(2)

1-2ではWordpressをApache + PHP + MySQLで動作させたが、今度はNginx + PHP + MariaDBで動作させます。

Nginxはディストリビューターからrpmが提供されていないため、リポジトリを追加する必要があります。
[公式サイト](http://nginx.org/en/linux_packages.html#stable)からリポジトリ追加用のrpmをダウンロードしてインストールしてください。


###Nginx
- Nginxのインストール

    sudo yum -y install http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm

- パッケージのインストール

    sudo yum install --enablerepo=nginx nginx

- 設定  
設定してねっ！

    /etc/nginx/conf.d/default.conf

    server {
        listen       80;
        server_name  localhost;

        root     /var/www/wordpress/;

        #charset koi8-r;
        access_log  /var/log/nginx/wp-access_log  main;
        error_log   /var/log/nginx/wp-error_log;

        location / {
        alias /var/www/wordpress/;
            index index.php index.html index.htm;
        }

        #error_page  404              /404.html;

        # redirect server error pages to the static page /50x.html
        #
        #error_page   500 502 503 504  /50x.html;
        #location = /50x.html {
        #    root   /usr/share/nginx/html;
        #}

        # proxy the PHP scripts to Apache listening on 127.0.0.1:80
        #
        #location ~ \.php$ {
        #    proxy_pass   http://172.168.40.1:8888;
        #}

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        #location ~ /\.ht {
        #    deny  all;
        #}
    }

###mariadb
- mariadbを起動する。

    sudo systemctl start mariadb

- mariadbを自動起動

    sudo systemctl enable mariadb

###php
- php

    yum install --enablerepo=epel,remi-php70 php php-mbstring php-pear php-fpm php-mcrypt php-mysql

起動

    sudo systemctl start php-fpm

再起
    
    sudo systemctl restart php-fpm

NginxでPHPを動かすにはコツが必要ですのでがんばって検索して動かしてください。
ヒントは **Nginx php-fpm** です。

Appacheでもphp-fpmでできるが  
Appacheは、Webサーバでプログラムを動かしていたが、Webサーバが重くなってしまったためphp-fpmという別サーバをつくりWebサーバからphp-fpmサーバにアクセスしてプログラムを動かすこれがNginxのやりかた

## 2-3 Wordpressを動かす(3)

1-2や2-2では提供されているrpmファイルを使用してLAMP/Nginx + PHPの環境を構築しましたが、
提供されていないバージョンを使用して環境を構築する必要がある時もあります。

そういう場合はソースコードをダウンロードしてきて自分でコンパイルして動作させます。

Apache HTTP Server 2.2とPHP7.0の環境を構築し、Wordpressを動かしてください。
(MySQL/MariaDBはrpm版を使ってもOKです)

その時は別のVagrantfile(作業ディレクトリ)を作ってやってくださいね。

apache  
[Document varsion2.2](https://httpd.apache.org/docs/2.2/install.html)

[Download varsion2.2](http://ftp.riken.jp/net/apache//httpd/httpd-2.2.31.tar.gz)

起動  

    /usr/local/apache2/bin/apachectl start

再起

  ｋ  /usr/local/apache2/bin/apachectl restart

phpで最後詰んだので

[phpDocument](https://secure.php.net/manual/ja/install.unix.apache2.php)

***Documentここだけ違うよっ( ^ω^ )ﾆｺﾆｺ***

    ./configure --with-apxs2=/usr/local/apache2/bin/apxs --with-mysqli

## 2-4 ベンチマークを取る

サーバーの性能測定のためにベンチマークを取ることがあります。

[別ページ](misc/Benchmark.md)にまとめてありますのでそちらを参照してください。

abコマンドをapt-getして、入れる  

    ab http://アドレス/
