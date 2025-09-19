


# insecure_webapp

This project is for cybersecurity and educational purposes where intentional mistakes are made in order to be exploited 

Application Layer Attacks:
https://docs.google.com/document/d/1-n4gv-DzNCoz3LsUp1bWw2W47BVQsMbtibpdAnkSl8I/edit?usp=sharing


Virtual Machine Details:


Host: Proxmox VE
OS: Ubuntu-25.04.live-server-amd64
Cores: 4
RAM: 4096
QEMU agent: Yes


## LAMP Install:

Update Repositories and install

	sudo apt update 
	sudo apt upgrade -y

 Install Apache
 
	sudo apt install apache2

 install MYSQL
 
	sudo apt install mysql-server

 Install PHP
 
	sudo apt install php libapache2-mod-php php-mysql

## Database creation:

	sudo mysql

Create DB for insecure_app

	CREATE DATABASE insecure_app_db;

Use Database

	USE insecure_app_db;

Create users table

	CREATE TABLE users (
  	     id INT AUTO_INCREMENT PRIMARY KEY,
  	     username VARCHAR(100) NOT NULL UNIQUE,
     password VARCHAR(255) NOT NULL
   	);

Create database user with weak credentials

	CREATE USER 'php_user'@'localhost' IDENTIFIED BY 'password123';

Grant all privileges to user (Do Not Do This in a real production environment

	GRANT ALL PRIVILEGES ON insecure_app_db.* TO 'php_user'@'localhost';

Apply Privileges

	FLUSH PRIVILEGES;

Exit MYSQL

	EXIT;


## Apache Permissions:


	sudo chown -R www-data:www-data /var/www/html/insecure_webapp

	sudo chmod -R 775 /var/www/html/insecure_webapp

## Modsecurity setup

Install modsecurity2

	sudo apt install libapache2-mod-security2 -y

 Enable Modsecurity2

	sudo a2enmod security2

 Copy config file

	sudo cp /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf

 Change SecRuleEngine to On

	if grep -q "SecRuleEngine" /etc/modsecurity/modsecurity.conf; then

   		sudo sed -i 's/^\s*SecRuleEngine\s\+DetectionOnly/SecRuleEngine On/' /etc/modsecurity/modsecurity.conf
    	echo "SecRuleEngine updated to On."
	
	else

    	echo "SecRuleEngine directive not found in modsecurity.conf"
	
	fi

## OWASP CoreRuleSet config

Install git and clone the repo

	sudo apt install git -y

	sudo git clone https://github.com/coreruleset/coreruleset.git /etc/modsecurity/coreruleset

 config file setup

 	cd /etc/modsecurity/coreruleset
  
	sudo cp crs-setup.conf.example crs-setup.conf


check for syntax errors in config files

	sudo apache2ctl -t

restart apache

	sudo systemctl restart apache2


