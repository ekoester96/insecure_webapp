# insecure_webapp

This project is for cybersecurity and educational purposes where intentional mistakes are made in order to be exploited 

Virtual Machine Details:
Host: Proxmox VE
OS: Ubuntu-25.04.live-server-amd64
Cores: 4
RAM: 4096
QEMU agent: Yes

LAMP Install:
1. sudo apt update 
2. sudo apt upgrade -y
3. sudo apt install apache2
4. sudo apt install mysql-server
5. sudo apt install php libapache2-mod-php php-mysql

Database creation:
1. sudo mysql
	
2. CREATE DATABASE insecure_app_db;

3. USE insecure_app_db;

4. CREATE TABLE users (
  	     id INT AUTO_INCREMENT PRIMARY KEY,
  	     username VARCHAR(100) NOT NULL UNIQUE,
     password VARCHAR(255) NOT NULL
   );

5. CREATE USER 'php_user'@'localhost' IDENTIFIED BY 'password123';

6. GRANT ALL PRIVILEGES ON insecure_app_db.* TO 'php_user'@'localhost';

7. FLUSH PRIVILEGES;

8. EXIT;

