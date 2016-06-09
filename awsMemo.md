#AWSのメモ

playbookは作ってあるので、設定を少し書き換えた。  
playbookのIPアドレスとnginxのdefault.confの置き場所

[AWS入門](http://qiita.com/hiroshik1985/items/6433d5de97ac55fedfde)

#6_0

[CLI](http://docs.aws.amazon.com/ja_jp/cli/latest/userguide/installing.html)

#6_1  
ansibleが終わってれば、grusにつないでkeypareやって繋ぐだけ。  
EC2に繋ぐときはkeyの場所でやるか、指定する必要性がある。

[EC2setUp](http://docs.aws.amazon.com/ja_jp/AWSEC2/latest/UserGuide/get-set-up-for-amazon-ec2.html)

##AMI(Amazon Machine Image)を作る

ダッシュボードからAMIを開いて、作成

#6_2 AWS EC2(AMIMOTO)
[nantekakebaiikawakaranai](https://ja.amimoto-ami.com/support/how-to-use/amazonconsole/)

#6_4 CloudFront
表示を高速化！大量アクセスに対応！サーバー引っ越し不要！すぐ始められて低価格！

Create Sistribution → Get Started → AMIで作ったやつをDomain Nameで指定する。作成  
作成したらDomain Nameでggr

#6_5 ELB  
EC2でAMI作ってインスタンス作成して、つなげた。  
EC2の画面でロードバランサーをやって、作成で注意するのはpingパスを/index.htmlを/に変更  
それぞれに接続して/var/log/nginx/access.logをみる。