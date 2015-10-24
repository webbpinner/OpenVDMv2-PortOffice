[OpenVDMv2PO_Logo]: http://www.oceandatarat.org/wp-content/uploads/2015/10/openVDMv2PO_Logo_long.png "Open Vessel Data Managment v2 - Port Office" 

![OpenVDMv2PO_Logo]
# OpenVDMv2 - PortOffice v0.1

## Install Guide

At the time of this writing OpenVDMv2 - Port Office was built and tested against the Xubuntu 14.04 LTS operating system. It may be possible to build against other linux-based operating systems however for the purposes of this guide the instructions will assume Xubuntu 14.04 LTS is used.

###Operating System
Goto <http://xubuntu.org/getxubuntu/>

Download Xubuntu for your hardware. At the time of this writing we are using 14.04.3 (32-bit)

Perform the default Xubuntu install. For these instructions the default account that is created is "Survey" and the computer name is "PortOffice".

A few minutes after the install completes and the computer restarts, Xubuntu will ask to install any updates that have arrived since the install image was created. Perform these now and do not continue with these instructions until the update has completed.

Before OpenVDMv2 can be installed serveral other services and software packaged must be installed and configured.

###MySQL Database Server
All of the commonly used variables, tranfer profiles, and user creditials for OpenVDM are stored in a SQL database. This allows fast access to the stored information as well as a proven mechanism for multiple clients to change records without worry of write collisions. OpenVDM uses the MySQL open-source database server.

To install MySQL open a terminal window and type:
```
sudo apt-get install mysql-server
```

### PHP5
The language used to write the OpenVDMv2 web-interface is PHP.

To install PHP open a terminal window and type:
```
sudo apt-get install php5 php5-cli php5-mysql
```

###Apache2 Web-server

The OpenVDM web-application is served by the Warehouse via the Apache2 Web-Server

Apache2 is installed by Xubuntu by default but an Apache2 module must be enabled. To enable the additional module open a terminal window and type:

```
sudo a2enmod rewrite
```

After enabling the module the webserver must be restarted:

```
sudo service apache2 restart
```

###OpenVDMv2 - Port Office
Create the Required Directories

In order for OpenVDMv2 to properly store data serveral directories must be created on the PortOffice machine.

-**CruiseData** - This is the location where the Cruise Data directories will be located.

The Location of the **CruiseData** needs to be large enough to hold multiple cruises worth of data. In typical installation of OpenVDMv2 - Port Office, the location of the **CruiseData** is on dedicated hardware (internal RAID array). In these cases the volume is mounted at boot by the OS to a specific location (i.e. `/mnt/vault`). Instructions on mounting volumes at boot is beyond the scope of these installation procedures however.

For the purposes of these installation instructions the parent folder for **CruiseData** will be a large RAID array located at: /mnt/vault and the user that will retain ownership of these folders will be "survey"

```
sudo mkdir -p /mnt/vault/CruiseData
sudo chown -R survey:survey /mnt/vault/CruiseData
```

###Download the OpenVDM Files from Github

From a terminal window type:

```
sudo apt-get install git
git clone git://github.com/webbpinner/OpenVDMv2-PortOffice.git ~/OpenVDMv2-PortOffice
```

###Create OpenVDMv2 Database

To create a new database first connect to MySQL by typing:

```
mysql -h localhost -u root -p
```

Once connected to MySQL, create the database by typing:

```
CREATE DATABASE OpenVDMv2-PO;
```

Now create a new MySQL user specifically for interacting with only the OpenVDM database. In the example provided below the name of the user is openvdmDBUser and the password for that new user is oxhzbeY8WzgBL3.

```
GRANT ALL PRIVILEGES ON OpenVDMv2-PO.* To openvdmDBUser@localhost IDENTIFIED BY 'oxhzbeY8WzgBL3';
```
It is not important what the name and passwork are for this new user however it is important to remember the designated username/password as it will be reference later in the installation.

To build the database schema and perform the initial import type:

```
USE OpenVDMv2;
source ~/OpenVDMv2-PortOffice/OpenVDMv2-PO_db.sql;
```

Exit the MySQL console:

```
exit
```

##Install OpenVDMv2 Web-Application

Copy the web-application code to a directory that can be accessed by Apache

```
sudo cp -r ~/OpenVDMv2/var/www/OpenVDMv2 /var/www/
sudo chown -R root:root /var/www/OpenVDMv2
```
Create the two required configuration files from the example files provided.

```
cd /var/www/OpenVDMv2-PortOffice
sudo cp ./.htaccess.dist ./.htaccess
sudo cp ./app/Core/Config.php.dist ./app/Core/Config.php
```

Modify the two configuration files.

Edit the .htaccess file:

```
sudo nano /var/www/OpenVDMv2-PortOffice/.htaccess
```

Set the RewriteBase to part of the URL after the hostname that will become the landing page for OpenVDMv2 - Port Office. By default this is set to OpenVDMv2 - Port Office meaning that once active users will go to `http://OpenVDMv2-PortOffice/`.

Edit the ./app/Core/Config.php file:

```
sudo nano /var/www/OpenVDMv2/app/Core/Config.php
```

Set the file URL of the OpenVDMv2 - Port Office installation. Look for the following lines and change the IP address in the URL to the actual IP address or hostname of the warehouse:

```
//site address
define('DIR', 'http://127.0.0.1/OpenVDMv2-PortOffice/');
```

**A word of caution.** The framework used by OpenVDMv2 - Port Office does not allow more than one URL to access the web-application. This means that you can NOT access the web-application using the machine hostname AND IP. You must pick one. Also with dual-homed machines you CAN NOT access the web-application by entering the IP address of the interface not used in this configuration file. Typically this is not a problem since dual-homed installation are dual-homed because the Warehouse is spanning a public and private subnet. While users on the the public subnet can't access machines on the private network, users on the private network can access machines on the public network. In that scenario the URL should be set to the Warehouse's interface on the public network, thus allowing users on both subnets access.

Set the access creditials for the MySQL database. Look for the following lines and modify them to fit the actual database name (`DB_NAME`), database username (`DB_USER`), and database user password (`DB_PASS`).

```
//database details ONLY NEEDED IF USING A DATABASE
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'OpenVDMv2-PO');
define('DB_USER', 'openvdmDBUser');
define('DB_PASS', 'oxhzbeY8WzgBL3');
define('PREFIX', 'OVDM-PO_');
```
Edit the default Apache2 VHost file.

```
sudo nano /etc/apache2/sites-available/000-default.conf
```

Copy text below into the Apache2 configuration file just above </VirtualHost>. You will need to alter the directory locations to match the locations selected for the CruiseData, PublicData and VisitorInformation directories:

```
  Alias /OpenVDMv2 /var/www/OpenVDMv2-PortOffice
  <Directory "/var/www/OpenVDMv2-PortOffice">
    AllowOverride all
  </Directory>

  Alias /CruiseData/ /mnt/vault/CruiseData/
  <Directory "/mnt/vault/FTPRoot/CruiseData">
    AllowOverride None
    Options -Indexes +FollowSymLinks +MultiViews
    Order allow,deny
    Allow from all
    Require all granted
  </Directory>
```

Reload Apache2

```
sudo service apache2 reload
```
