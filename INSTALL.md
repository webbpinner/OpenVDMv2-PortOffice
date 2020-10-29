# OpenVDM - PortOffice v2.2

## Install Guide

At the time of this writing OpenVDMv2 - Port Office was built and tested against the Ubuntu 18.04 LTS operating system. It may be possible to build against other linux-based operating systems however for the purposes of this guide the instructions will assume Ubuntu 18.04 LTS is used.

### Operating System
Goto <https://www.ubuntu.com/download>

Download Uubuntu for your hardware. At the time of this writing we are using 18.04 (64-bit)

Perform the default Uubuntu install. For these instructions the default account that is created is "survey" and the computer name is "PortOffice".

A few minutes after the install completes and the computer restarts, Ubuntu will ask to install any updates that have arrived since the install image was created. Perform these now and do not continue with these instructions until the update has completed.

Before OpenVDMv2 - Port Office can be installed serveral other services and software packaged must be installed and configured.

### MySQL Database Server
All of the commonly used variables, tranfer profiles, and user creditials for OpenVDM - Port Office are stored in a SQL database. This allows fast access to the stored information as well as a proven mechanism for multiple clients to change records without worry of write collisions. OpenVDM - Port Office uses the MySQL open-source database server.

To install MySQL open a terminal window and type:
```
sudo apt-get install mysql-server
```

### PHP7.3
The language used to write the OpenVDMv2 web-interface is PHP.

To install PHP open a terminal window and type:
```
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/apache2
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/pkg-gearman

sudo apt-get update

sudo apt-get install php7.3 php7.3-cli php7.3-mysql php7.3-dev php7.3-zip php7.3-curl php-yaml
```

### Apache2 Web-server

The OpenVDMv2-PortOffice web-application is served by the Warehouse via the Apache2 Web-Server

Apache2 is installed by Ubuntu by default but additional Apache2 modules must be install and enabled. 

#### Rewrite
To enabled the rewrite module open a terminal window and type:

```
sudo a2enmod rewrite
```

#### PHP
To install/enabled the php module open a terminal window and type:

```
sudo apt-get install libapache2-mod-php7.3
```

#### Finally
After installing/enabling the module the webserver must be restarted:
```
sudo service apache2 restart
```

### OpenVDMv2 - Port Office
Create the Required Directories

In order for OpenVDMv2 - Port Office to properly store data serveral directories must be created on the PortOffice server.

- **CruiseData** - This is the location where the incoming Cruise Data directories will be located.

The Location of the **CruiseData** needs to be large enough to hold multiple cruises worth of data. In typical installation of OpenVDMv2 - Port Office, the location of the **CruiseData** is on dedicated hardware (internal RAID array). In these cases the volume is mounted at boot by the OS to a specific location (i.e. `/mnt/vault`). Instructions on mounting volumes at boot is beyond the scope of these installation procedures.

For the purposes of these installation instructions the name of the **CruiseData** folder will be `Shoreside`, it will be located at `/mnt/vault` and the user that will retain ownership of this folders will be "survey"

```
sudo mkdir -p /mnt/vault/Shoreside
sudo chown -R survey:survey /mnt/vault/Shoreside
```

### Download the OpenVDM Files from Github

From a terminal window type:

```
sudo apt-get install git
git clone git://github.com/webbpinner/OpenVDMv2-PortOffice.git ~/OpenVDMv2-PortOffice
```

### Create OpenVDMv2 Database

To create a new database first connect to MySQL by typing:

```
sudo mysql -h localhost -u root
```

Once connected to MySQL, create the database by typing:

```
CREATE DATABASE OpenVDMv2_PO;
```

Now create a new MySQL user specifically for interacting with only the OpenVDM database. In the example provided below the name of the user is 'openvdmDBUser' and the password for that new user is 'oxhzbeY8WzgBL3'.

```
GRANT ALL PRIVILEGES ON OpenVDMv2_PO.* To openvdmDBUser@localhost IDENTIFIED BY 'oxhzbeY8WzgBL3';
```
It is not important what the name and passwork are for this new user however it is important to remember the designated username/password as it will be reference later in the installation.

To build the database schema and perform the initial import type:

```
USE OpenVDMv2_PO;
source ~/OpenVDMv2-PortOffice/OpenVDMv2-PO_db.sql;
```

Exit the MySQL console:

```
exit
```

## Install OpenVDMv2 - Port Office Web-Application

Copy the datadashboard configuration file to the proper location.  This will require creating a directory.

```
sudo mkdir -p /usr/local/etc/openvdm
sudo cp -r ~/OpenVDMv2-PortOffice/usr/local/etc/openvdm/datadashboard.yaml.dist /usr/local/etc/openvdm/datadashboard.yaml
sudo chown -R root:root /usr/local/etc/openvdm
```

Copy the web-application code to a directory that can be accessed by Apache

```
sudo cp -r ~/OpenVDMv2-PortOffice/var/www/OpenVDMv2-PortOffice /var/www/
sudo chown -R root:root /var/www/OpenVDMv2-PortOffice
```

Create the web-application errorlog file

```
sudo touch /var/www/OpenVDMv2-PortOffice/errorlog.html
sudo chmod 777 /var/www/OpenVDMv2-PortOffice/errorlog.html
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

Set the RewriteBase to part of the URL after the hostname that will become the landing page for OpenVDMv2 - Port Office. By default this is set to OpenVDMv2 - Port Office meaning that once active users will go to `http://<IP or Hostname>/OpenVDMv2-PortOffice/`.

Edit the ./app/Core/Config.php file:

```
sudo nano /var/www/OpenVDMv2-PortOffice/app/Core/Config.php
```

Set the cruise data base directory to what was created for **CruiseData**. Look for the following lines and change the IP address in the URL to the actual IP address or hostname of the warehouse:

```
/*
* Define path on webserver that contains cruise data
*/
define('CRUISEDATA_BASEDIR', '/mnt/vault/Shoreside');

```

Set the access creditials for the MySQL database. Look for the following lines and modify them to fit the actual database name (`DB_NAME`), database username (`DB_USER`), and database user password (`DB_PASS`).

```
/*
 * Database name.
 */
define('DB_NAME', 'OpenVDMv2_PO');

/*
 * Database username.
 */
define('DB_USER', 'openvdmDBUser');

/*
 * Database password.
 */
define('DB_PASS', 'oxhzbeY8WzgBL3');
```

Edit the default Apache2 VHost file.

```
sudo nano /etc/apache2/sites-available/000-default.conf
```

Copy the text below to the end Apache2 VHost file just above `</VirtualHost>`. You will need to alter the directory locations to match the locations selected for the CruiseData directory:

```
  Alias /OpenVDMv2-PortOffice /var/www/OpenVDMv2-PortOffice
  <Directory "/var/www/OpenVDMv2-PortOffice">
    AllowOverride all
  </Directory>

  Alias /Shoreside /mnt/vault/Shoreside
  <Directory "/mnt/vault/Shoreside">
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

At this point OpenVDMv2 - Port Office should be installed and awaiting incoming data.
Goto to the URL for OpenVDMv2 - Port Office as defined earlier.
The default username/password are: admin/demo.

It is recommended to change the password as soon as possible.

### An error has occured
If after the install process the message "An error has occured" appears on the web-interface please refer to the errorlog file for more information on what exactly has happened.  The error log is html-formatted and can viewed in the browser at the following address:
```
http://<ip address>/OpenVDMv2-PortOffice/errorlog.html
```

### Connecting OpenVDMv2 running on a vessel to OpenVDMv2 - Port Office

For security reasons the connection between OpenVDMv2 and OpenVDMv2 - Port Office must be initiated from the vessel.  The remainder of the instructions within this section are meant to be executed within the OpenVDMv2 installation on the vessel.

#### Configure the Shoreside Data Warehouse
From the OpenVDMv2 web-application goto the "System" tab in the "Configuration" section.

Click the "Edit" link next to Shoreside Data Warehouse (SSDW)

Complete the "Edit Shoreside Data Warehouse" form using the appropriate information from this installation.

When complete click the "Update" button to save the changes.

To verify the configuration is correct click the "Test" link next to "Shoreside Data Warehouse (SSDW)".

#### Enable the Required Ship-to-Shore Transfer profiles
From the OpenVDMv2 web-application goto the "System" tab in the "Configuration" section.

Within the **OpenVDM Specific Ship-to-Shore Transfers** panel, make sure the "Dashboard Data" AND "OpenVDM Configuration" transfers are enabled.

#### Manually Initiating a Ship-to-Shore Transfer
To manually initiate a ship-to-shore transfer goto the "Ship-to-Shore Transfers" tab in the "Configuration" section.

Click the "Run Ship-to-Shore Transfer"

#### Automatic Ship-to-Shore Transfers
While OpenVDMv2 System Status is "On" AND Ship-to-Shore Tranfers are enabled, OpenVDMv2 will automatically attempt to push new data to shore every 5 minutes.

#### Custom Data Dashboard configuration
If the data dashboard running on OpenVDM has been customized and you wish to have the same look/feel with PortOffice, replace the default datadashboard.yaml file with the one used by OpenVDM.  If custom javascript has been developed to further customize the datadashboard in OpenVDM, copy those view (.php) and javascript (.js) files to the cooresponding locations in PortOffice.
