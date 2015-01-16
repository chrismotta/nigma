## Kickads Appserver
### Install Environment

#### Programs
- Sublime
- SmartGitHg
- MySqlWorkbench
- Synaptic Package Manager
- Skype

#### Package  
- git
- openjdk-7-jre
- see https://help.ubuntu.com/community/ApacheMySQLPHP for complete tutorial
  - apache2
  - libapache2-mod-php5
  - mysql-server libapache2-mod-auth-mysql php5-mysql
- phpmyadmin
- php5-curl
- Clone yii source code
```
cd /var/www/html/
sudo git clone https://github.com/yiisoft/yii.git yii
```
- Pecl|Pear
```
apt-get install php-pear php5-dev
```
- PCRE
```
apt-get install libpcre3 libpcre3-dev
```
- OAuth
```
pecl install oauth
vi /etc/php5/apache2/php.ini
add 'extension=oauth.so'
service apache2 restart
```

#### Configurations 
- Copy Ip2Location.BIN and create Wurlf Folders
- Change permissions for apache folder
```
sudo chmod 777 /var/www/html
```
- Create intial test for php and apache
```
echo "<?php phpinfo(); ?>" >> /var/www/html/phpinfo.php
```
- Enable workspace
  - http://askubuntu.com/questions/260510/how-do-i-turn-on-workspaces-why-do-i-only-have-one-workspace
- Change Behavior executable text files
  - http://askubuntu.com/questions/83470/how-do-i-change-how-executable-files-are-handled-by-the-file-manager
- Edit smartgithg.sh to correct openjdk-7-jre path
- Create db kickads_appserver and import backup_data
```
sudo gedit /etc/apache2/apache2.conf AllowOverride All in /var/www section and execute sudo a2enmod rewrite
sudo chmod -R 777 /var/www/html/kickads/appserver
- Ignore chmod changes in git
```
cd /var/www/html/kickads/appserver 
git config core.fileMode false
- Plugins Sublime
  - https://sublime.wbond.net/installation#st2
  - http://www.pixmatstudios.com/blog/10-plugins-sublime-text-pixmat/#.VCG2Cq24E8o


