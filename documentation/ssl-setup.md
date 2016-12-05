## Nigma Appserver
### Setup SSL on production



#### Packages
- Apache2
- mod_ssl
	- check if installed: dpkg -S mod_ssl.so



#### Create certificate

- Create Certificate Signing Request and Key
```
openssl req -new -newkey rsa:2048 -nodes -keyout yourdomain.key -out yourdomain.csr
```

- Previous command generate 2 files (.csr and .key). Copy .csr file contents and use it your certificate request.


- Once certificate request is successfull you get two .crt files ( certificate and group certificate ).



#### Configure Apache2 HTTPS virtual host

- Edit Apache sites-available:
```
sudo vim /etc/apache2/sites-available/default-ssl.conf
```


- Add the following virtual host lines:

```
        <VirtualHost _default_:443>

                ServerAdmin admin@bidbox.co
                ServerName beta.bidbox.co
                DocumentRoot /var/www/html/beta
                #ErrorLog ${APACHE_LOG_DIR}/error.log
                #CustomLog ${APACHE_LOG_DIR}/access.log combined

                SSLEngine on

                SSLCertificateFile /etc/apache2/ssl/70fe8ef088766cae.crt
                SSLCertificateKeyFile /etc/apache2/ssl/bidbox.key
                SSLCACertificateFile /etc/apache2/ssl/gd_bundle-g2-g1.crt

                <FilesMatch "\.(cgi|shtml|phtml|php)$">
                                SSLOptions +StdEnvVars
                </FilesMatch>
                <Directory /usr/lib/cgi-bin>
                                SSLOptions +StdEnvVars
                </Directory>

                BrowserMatch "MSIE [2-6]" \
                                nokeepalive ssl-unclean-shutdown \
                                downgrade-1.0 force-response-1.0
                # MSIE 7 and newer should be able to use keepalive
                BrowserMatch "MSIE [17-9]" ssl-unclean-shutdown

        </VirtualHost>
```


- Repeat al revious steps on /etc/apache2/sites-enabled/default-ssl.conf


- Load Apache SSL module
```
a2enmod ssl
```


- Load Apache SSL module
```
sudo a2ensite default-ssl.conf
```


- Check Apache config
```
apache2ctl configtest
```


- Load Apache config (without restarting service)
```
sudo apache2 reload
```


- Test if Apache is listening on  HTTPS
```
netstat -tap | grep https
```