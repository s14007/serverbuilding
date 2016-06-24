# Section 3 Ansibleによる自動化とテスト

毎回毎回手動で

    yum install もにょもにょ

とやるのも非効率なので、それらを自動化してくれるツールを使って今迄の作業を何回でもできるようにします。

今回の講義ではAnsibleを使用します。

## 3-0 Ansibleのインストール

[公式サイト](http://docs.ansible.com/intro_installation.html#latest-releases-via-apt-ubuntu)に手順載ってるのでそのとおりにやってください。

## 3-1 ansibleでWordpressを動かす(2)を行なう

2-1でWordpressをNginx + PHP + MariaDBでインストールした手順をAnsibleのPlaybookで実行するように記述し、動かしてみてください。

VagrantfileからAnsibleを実行することもできますが、最初は普通に使用することをおすすめします。

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

### 3-1-2? VagrantfileからAnsibleを呼び出す

ggr(WIP)
