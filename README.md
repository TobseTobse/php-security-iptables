# apache-iptables-security (sesame)
These scripts protect a server running Apache, PHP and iptables against SSH brute forcing or attacks on port 22

## Installation

First clone this repository to /tmp and then move the files to their regarding directories.

Then edit /usr/share/sesame/.htpasswd and put your credentials there (search with your preferred search engine for "htpasswd generator", copy & paste).

`nano /usr/share/sesame/.htpasswd`

The sesame directory (/usr/share/sesame) and /etc/iptables.up.rules must be writable by the webserver user:
```
sudo chown -R www-data:www-data /usr/share/sesame
sudo touch /etc/iptables.up.rules
sudo chown www-data:www-data /etc/iptables.up.rules
```

Now extend your Apache config like in /etc/apache2/sites-available/mydomain.com-le-ssl.conf in this project.

`nano /etc/apache2/sites-available/mydomain.com-le-ssl.conf`

Search for "sesame" there. Ensure the first <Directory> directive is **OUTSIDE** your <VirtualHost> configuration and the `Alias /sesame` directive is **INSIDE** your <VirtualHost> configuration.
Save and restart Apache:

`sudo service apache2 graceful`

> [!CAUTION]
> !!!!!!!!!!!!!!!!!!!!!!!!!!!
>
> TEST WEB FRONTEND at https://yourdomain.com/sesame. Login with the credentials you have specified in /usr/share/sesame/.htpasswd in the first step of this manual. Then "Open sesame for myself".
>
> Check that /usr/share/sesame/closetime.stamp was created. If it wasn't created, go back to the beginning of this manual and find your mistake.
>
> **Without a working web frontend you WILL NOT BE ABLE TO UNLOCK YOUR SERVER anymore!**
>
> Thou had been warnt, friend!
>
> !!!!!!!!!!!!!!!!!!!!!!!!!!!

Next create an iptables save file:

`sudo iptables-save > /usr/share/sesame/iptables.up.rules`

Then edit /usr/share/sesame/iptables.up.rules and add the following lines (remove the first three lines if you don't have or want any other static IPs you could connect from to this server via SSH in an emergy case). Remove the line with port 10000 if you don't use Webmin or add a line for another port accordingly.

`nano /usr/share/sesame/iptables.up.rules`

>  [!CAUTION]
> **!!! DO NOT REMOVE OR MODIFY THE REMARKS. PHP NEEDS THEM TO RECOGNIZE THE BLOCK START AND END !!!**

```
# Always grant SSH to the following IPs
-I INPUT -p tcp -s 11.22.33.44 --dport 22 -j ACCEPT
-I INPUT -p tcp -s 9.9.9.9 --dport 22 -j ACCEPT
# OpenSesame allow start
# OpenSesame allow end
# OpenSesame block start
-A INPUT -p tcp -m tcp --dport 22 -j DROP
-A INPUT -p tcp -m tcp --dport 10000 -j DROP
# OpenSesame block end
COMMIT
```

Adjust the file /usr/share/sesame/ports to your needs:

`nano /usr/share/sesame/ports`

...and **edit the configuration part in /usr/share/sesame/autoclose.php** so that you are informed about potential system failures.

Check again if everything from above has been done correctly. If everything is OK edit the root crontab...

```
sudo su
crontab -e
```

... and add the following lines (don't forget about the **blank last line** before you save)

```
# restore iptables
*       *       *       *       *       /usr/sbin/iptables-restore < /etc/iptables.up.rules

# auto close forgotten connections
0       *       *       *       *       /usr/bin/php/php /usr/share/semsame/autoclose.php

```

Grats, you should be good now!
Go back to https://yourdomain.com/sesame and get familiar with the options.
