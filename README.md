# GoDaddy Tools

A collection of tools to interact with GoDaddy

👩‍💻 [GitHub](https://github.com/TurboLabIt/godaddy-tools)

🔑 [API key management](https://developer.godaddy.com/keys)


# How to install

1. [Install Webstackup](https://github.com/TurboLabIt/webstackup)
1. Update webstackup: `bash /usr/local/turbolab.it/webstackup/setup.sh`

````shell
sudo -u webstackup -H git clone https://github.com/TurboLabIt/godaddy-tools.git /var/www/godaddy-tools
bash /var/www/godaddy-tools/scripts/deploy.sh
sudo -u webstackup -H nano /var/www/godaddy-tools/.env.prod.local

````


# How to run

No need. It runs [via its own cron](https://github.com/TurboLabIt/godaddy-tools/blob/master/config/custom/cron). The 
cron file is deployed by [deploy.sh](https://github.com/TurboLabIt/godaddy-tools/blob/master/scripts/deploy.sh).

If you still want to run it manually:


````shell
bash /var/www/godaddy-tools/scripts/app-download-domains.sh

````

The generated files are in `/var/www/godaddy-tools/var/domains/*.csv`. CSV older than *some* days are deleted 
via [cleaner.sh](https://github.com/TurboLabIt/godaddy-tools/blob/master/scripts/cleaner.sh) (executed via cron).


# How to update

No need. It updates [automatically](https://github.com/TurboLabIt/godaddy-tools/blob/master/scripts/auto-update.sh) 
every day via cron, if a new revision was merged on *master*.

If you still want to update it manually:

````shell
bash /var/www/godaddy-tools/scripts/deploy.sh

```` 
