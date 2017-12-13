# FileShare

Web app built using Symfony3.

## Amazon Linux Installation

1. Install git

    ``sudo yum install -y git``

2. Retrieve source code

    ``git clone https://github.com/jmgtan/fileshare.git``

3. Install PHP runtime (this will also install Apache HTTPD):

    ``sudo yum install -y php71 php71-cli php71-intl php71-mbstring php71-mcrypt php71-mysqlnd``

4. Install composer

    ``php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"``
    
    ``php composer-setup.php``
    
    ``php -r "unlink('composer-setup.php');"``
    
    ``sudo mv composer.phar /usr/bin/composer``

5. Install application dependencies (in the fileshare folder)

    ``composer install``

6. When prompted, enter the database connection details. You can leave mailer and secret token values to its default value.

7. Initialized the database:

    ``bin/console doctrine:schema:update --force``
    
8. Complete Apache virtual host setup and point it to the fileshare/web folder. Make sure that Override is allowed for the url rewrite.