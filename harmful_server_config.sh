#!/bin/bash
# Warning DO NOT RUN THIS SCRIPT in any environment other than a test environment
# Used with PHP version 8.4 and Apache2
# Work in progress


# replace <ver> with your version (or run ls /etc/php and use what's listed)
VER="8.4"

# Copy all server configs as backups

sudo cp /etc/php/$VER/apache2/php.ini /etc/php/$VER/apache2/php.ini.bak
sudo cp /etc/apache2/apache2.conf /etc/apache2/apache2.conf.bak
sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf.bak

# Expose PHP version in the response headers from the server to the client
sudo sed -i "s/^expose_php = .*/expose_php = On/" /etc/php/$VER/apache2/php.ini

# Expose a risk for RFI 
sudo sed -i "s/^allow_url_fopen = .*/allow_url_fopen = On/" /etc/php/$VER/apache2/php.ini
sudo sed -i "s/^allow_url_include = .*/allow_url_include = On/" /etc/php/$VER/apache2/php.ini

# Expose Server version and OS in headers
sudo sed -i "s/^ServerSignature .*/ServerSignature On/" /etc/apache2/conf-available/security.conf
sudo sed -i "s/^ServerTokens .*/ServerTokens Full/" /etc/apache2/conf-available/security.conf

if sudo grep -q "^open_basedir" /etc/php/$VER/apache2/php.ini 2>/dev/null; then
  sudo sed -i "s|^open_basedir.*|open_basedir = /|" /etc/php/$VER/apache2/php.ini
fi

# Making backups.txt file accessible
sudo mkdir -p /var/www/html/backups
echo "DB backup contents" | sudo tee /var/www/html/backups/backup.txt
# Ensure readable by web server:
sudo chown -R www-data:www-data /var/www/html/backups
sudo chmod -R 755 /var/www/html/backups

# enable indexing
sudo a2enmod autoindex

FILE=/etc/apache2/sites-available/000-default.conf
DOCROOT="/var/www/html"

if grep -q "<Directory ${DOCROOT}>" "$FILE"; then
  # If an Options line exists inside the Directory block, replace it
  if sed -n "/<Directory ${DOCROOT}>/,/<\/Directory>/p" "$FILE" | grep -q "^[[:space:]]*Options"; then
    sudo sed -i "/<Directory ${DOCROOT}>/,/<\/Directory>/ s/^[[:space:]]*Options.*/    Options Indexes FollowSymLinks/" "$FILE"
  else
    # Otherwise, add an Options line right after the <Directory ...> line
    sudo sed -i "/<Directory ${DOCROOT}>/ a\    Options Indexes FollowSymLinks" "$FILE"
  fi
else
  # Insert a new Directory block just before </VirtualHost>
  sudo sed -i "/<\/VirtualHost>/ i\<Directory ${DOCROOT}>\n    Options Indexes FollowSymLinks\n    AllowOverride None\n    Require all granted\n</Directory>\n" "$FILE"
fi

# Show the relevant part so you can confirm
echo "----- /etc/apache2/sites-available/000-default.conf (relevant section) -----"
sed -n "/<Directory ${DOCROOT}>/,/<\/Directory>/p" "$FILE" || true

# Confirm changes with phpinfo.php file
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/phpinfo.php
sudo systemctl restart apache2



