#memo

##1_2 普通にwordpress
### VirtualBoxInstall

* VirtualBoxのインストール  
	- サイトいってインストールの手順通りにやる
* Vagrantのインストール 
	- 公式サイトからダウンロード、インストール  

### VirtualBoxの設定

* CentOSのISOイメージファイルをダウンロード
	- サイトから  
* ネットワークの設定
 	- File→Preferences→ネットワーク→Host-only networksに新規追加する
 	- CentOSのsettingsを開いてネットワークでさっき追加したものを選択する

### yum,wgetのプロキシ設定
yum,wgetでプロキシのアドレス書けばいいだけじゃ〜ん(^q^)

* 設定ファイルをいしる
 	- /etc/yum.confに  

 			proxy=http://172.16.40.1:8888

yumのproxy設定を変えるだけでは接続ができない事案発生( ﾟ∀ﾟ)･∵. ｸﾞﾊｯ!!  
まずはIPアドレスからだよっ！  

* ネットワーク設定（ゝω・）vｷｬﾋﾟ  
	- 設定ファイルをいじる  

	 		/etc/sysconfig/network-scripts/ifcfg-enp0s3  
	 		/etc/sysconfig/network-scripts/ifcfg-enp0s8  

	この2つのファイルのONBOOTをyesにする  

	- 有効化？する  

			sudo /etc/sysconfig/network-scripts/ifcfg-enp0s3  
			sudo /etc/sysconfig/network-scripts/ifcfg-enp0s8

	- IPアドレスの確認  

			ip addr

これでUbuntuから接続できるようになっているはず  
yumもできるはず

### WordPressのインストール

* MySQL  
	- MySQLの公式からyum directoryのパッケージダウンロード
* Appach  

		yum install httpd

* PHP  
	公式から持ってくるyum repository

		yum install

## Vagrantを使ってのwordpress
###Nginx,php,mariadbを使ってwordpressを使う  

* mariadb  
	- mariadbのインストール  
		
			sudo yum -y install mariadb mariadb-server

	- 初期設定  

			mysql_secure_installstion

	- mariadbを起動  
		
			sudo systemctl start mariadb

	- サーバを起動時にmariadbを有効化  
		
			sudo systemctl enable mariadb

	- 状態の確認  

			sudo systemctl status mariadb

	- mysqlでdatabaseを作成
* Nginx  
	- Nginxのインストール  

			sudo yum -y install http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm

	- 	パッケージのインストール  

			sudo yum install --enablerepo=nginx nginx

	- Nginxを起動  

			sudo systemctl start nginx

	- サーバを起動時にNginxを有効化  

			sudo systemctl enable nginx

	- 状態の確認  

			sudo systemctl status nginx

	- etc/nginx/conf.d/default.confを編集  
	 
* php 
	- phpのインストール  

			yum install --enablerepo=epel,remi-php70 php php-mbstring php-pear php-fpm php-mcrypt php-mysql

* wordpress  
	- wordpressをインストール  

	- var/www/に移動させた

	- 

### Apache HTTP Server 2.2, PHP7.0を使用してwordpressを動かす

Apacheからやったほうがいいらしい。

* Apache HTTP Server 2.2

	- ダウンロード  
		http://httpd.apache.org/download.cgi#apache22  

	- 公式を見ました。

		http://php.net/manual/ja/install.unix.apache2.php  

	- ./configure --enable-so  
		libxmlを入れろと怒られる

* PHP7.0
	
	- ダウンロード  
		http://php.net/downloads.php  
	公式を見ました。
	- php.iniの設定  
		mysql_default_socketでmysqlのpathを設定する。

## 3_0 ansible使って自動化

Vagrantfileがある場所に  

- roles
	- nginx
	- php-fpm
	- wordpress
- playbook.yml
- hosts
- Vagrantfile

という構成

hostsの中身はvagrantのIPアドレスだけ

ansible-playbook途中でできなくても実行続けたりもあったから気づかないこと多かった

php-fpm.sockがないとか言われた  
単純にwww.confの記述ミスで

	listen = /var/run/php-fpm/php-fpm.sock

忘れてただけ

サーバーにアクセスしようとしたらこれになった  

	[error] 959#0: *116 open() "/usr/local/nginx/html/favicon.ico" failed (2: No such file or directory), 
	client: 111.68.59.75, server: 127.0.0.1, request: "GET /favicon.ico HTTP/1.1"

なのでnginx/default.confにこれを追加した

	location = /favicon.ico {
  		log_not_found off;
	}