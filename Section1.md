# Section 1 基本のサーバー構築

## 1-1 CentOS 7のインストール

### VirtualBoxへのインストール

CentOSの公式サイトよりCentOS 7 Minimal ISO(x86_64)のISOファイルをダウンロードし、
VirtualBox上にインストールしてください。

VirtualBoxで作成する仮想マシンのメモリのサイズは1GBにします。また、ストレージの容量は8GB程度に設定してください。

作成したらNetWorkを追加するので File → Preferences → Network → Host-only Networks でAddする。

ネットワークアダプター2を設定します。割り当てを「ホストオンリーアダプター」にします。
(ネットワークアダプター1はデフォルト(NAT)で問題ありません)

インストール中に指示されるパーティションの設定は特に指定しません。

インストール中、root以外の作業用(管理者)のユーザーを作成してください。

### ネットワークアダプター1/2へのIPアドレスの設定とssh接続の確認

/etc/sysconfig/network-scriptにifcfg-enp0s?というファイルがあるので、
そのファイルを編集してネットワーク接続ができるように設定します。

	/etc/sysconfig/network-script/ifcfg-enp0s3
	/etc/sysconfig/network-script/ifcfg-enp0s8

このファイルをONBOOT=yesにする。

	sudo /etc/sysconfig/network-script/ifup enp0s3
	sudo /etc/sysconfig/network-script/ifup enp0s8

で、設定する。

DHCPでIPアドレスを取得できますので、[RedHat Enterprise Linux 7のマニュアル(英語)](https://access.redhat.com/documentation/en-US/Red_Hat_Enterprise_Linux/7/html-single/Networking_Guide/index.html#sec-Configuring_a_Network_Interface_Using_ifcg_Files)を読んで設定してください。

### SSH接続の確認

ipコマンドでIPアドレス確認してUbuntuから

	ssh username@IPaddress

Ubuntuからsshで仮想マシンに接続できることを確認してください。

(ついでなので、公開鍵認証でログインできるようにしておくといいと思いますよ。必須ではないけど。)

### インストール後の設定

yumやwgetを使用する時のproxyの設定を行なってください。

	/etc/yum.conf

で、

	proxy=http://192.16.40.1:8888

をどこかに記述する。

	/etc/wgetrc

に、httpproxy=みたいなのがあるので、

	http_proxy=http://172.16.40.1:8888

を設定。からの、

	sudo yum update

[proxy設定](http://d.hatena.ne.jp/mrgoofy33/20110125/1295966614)

## 1-2 Wordpressを動かす(1)

Wordpressを動作させるためには下記のソフトウェアが必要になります。 [※1](#LAMP)

* Apache HTTP Server
* MySQL
* PHP

これらをyumを使用してインストールし、Wordpressをダウンロード、展開して動作させてください。

とりあえず、yumリポジトリをもらってくる。  
このサイトから  
	[yumRepository](http://dev.mysql.com/downloads/repo/yum/)

	sudo yum -y install http://dev.mysql.com/get/mysql57-community-release-el7-8.noarch.rpm
で、  

	sudo yum -y install httpd mysql mysql-server mysql-devel mysql-utiliities php php-mysql wget

まずは、Apacheから

	sudo vi /etc/httpd/conf/httpd.conf

を、開き

	DocumentRoot "/var/www/wordpress"

と,Directoryのpathをwordpressにして、AllowOverrideをAllにする。

		# Further relax access to the default document root:
	<Directory "/var/www/wordpress">
	    #
	    # Possible values for the Options directive are "None", "All",
	    # or any combination of:
	    #   Indexes Includes FollowSymLinks SymLinksifOwnerMatch ExecCGI MultiViews
	    #
	    # Note that "MultiViews" must be named *explicitly* --- "Options All"
	    # doesn't give it to you.
	    #
	    # The Options directive is both complicated and important.  Please see
	    # http://httpd.apache.org/docs/2.4/mod/core.html#options
	    # for more information.
	    #
	    Options Indexes FollowSymLinks

	    #
	    # AllowOverride controls what directives may be placed in .htaccess files.
	    # It can be "All", "None", or any combination of the keywords:
	    #   Options FileInfo AuthConfig Limit
	    #
	    AllowOverride None

	    #
	    # Controls who can get stuff from this server.
	    #
	    Require all granted
	</Directory>

Appcheの設定が終わったので再起

	sudo systemctl restart httpd

mySQLをいじっていく  

起動の呪文  

	sudo systemctl start mysqld
	sudo systemctl enable mysqld

ついでに、

	sudo systemctl start httpd
	sudo systemctl enable httpd

なんか最近初期パスワードが振られてるんで[yumRepositoryDocument](http://dev.mysql.com/doc/refman/5.7/en/linux-installation-yum-repo.html)を見た。  

これで、初期パスワードを確認できる

	sudo grep 'temporary password' /var/log/mysqld.log

で、初期設定  

	mysql_secure_installation

最初のrootパスワードでさっきの入力して、新しくパスワードを作る。
それ以外はyでおけ。

終わったら、MySQLにはいって設定していく
今、rootなのでGRANTでユーザー作る。


	$ mysql -u root -p
	Enter password:
	Welcome to the MySQL monitor.  Commands end with ; or \g.
	Your MySQL connection id is 5340 to server version: 3.23.54
	 
	Type 'help;' or '\h' for help. Type '\c' to clear the buffer.
	 
	mysql> CREATE DATABASE databasename;
	Query OK, 1 row affected (0.00 sec)
	 
	mysql> GRANT ALL PRIVILEGES ON databasename.* TO "wordpressusername"@"localhost"
	    -> IDENTIFIED BY "password";
	Query OK, 0 rows affected (0.00 sec)
	  
	mysql> FLUSH PRIVILEGES;
	Query OK, 0 rows affected (0.01 sec)

	mysql> EXIT
	Bye

次、WordPress

Wordpressのインストールは[公式サイトに手順が掲載されています](http://wpdocs.sourceforge.jp/WordPress_%E3%81%AE%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB)のでそちらを参考にすると確実かと思います。

	wget http://wordpress.org/latest.tar.gz

	tar -xzvf latest.tar.gz

コピーでwwwの中に作成

	sudo cp -r wordpress/ /var/www/

/var/www/wordpress/wp-config-sample.phpをコピー

	sudo cp wp-config-sample.php wp-config.php

で、wp-config.phpを編集  
データベースで作った情報を入力

	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', 'database_name_here');

	/** MySQL database username */
	define('DB_USER', 'username_here');

	/** MySQL database password */
	define('DB_PASSWORD', 'password_here');

	/** MySQL hostname */
	define('DB_HOST', 'localhost');

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');

と、[認証キー](https://api.wordpress.org/secret-key/1.1/salt/)をコピーして貼り付け

	define('AUTH_KEY',         'put your unique phrase here');
	define('SECURE_AUTH_KEY',  'put your unique phrase here');
	define('LOGGED_IN_KEY',    'put your unique phrase here');
	define('NONCE_KEY',        'put your unique phrase here');
	define('AUTH_SALT',        'put your unique phrase here');
	define('SECURE_AUTH_SALT', 'put your unique phrase here');
	define('LOGGED_IN_SALT',   'put your unique phrase here');
	define('NONCE_SALT',       'put your unique phrase here');

これで、

	sudo systemctl restart mysqld
	sudo systemctl restart httpd

で、おけ

あとは、

	http://192.168.56.101

に接続  

できなかったら、/var/log/httpd/error_log  

***コピペダメ絶対！***

なお、ssh接続できるようになっているので、VirtualBoxの画面からではなく、UbuntuからSSHで接続して設定してください。
(そのほうが圧倒的に楽です。)

<a name="LAMP">※1</a>: Linux・Apache・MySQL・PHPの頭文字を取ってLAMPといいます。
