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
