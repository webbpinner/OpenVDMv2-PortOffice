# OpenVDMv2-PortOffice v0.1
The Shore-side component of OpenVDMv2

## Install Instructions
### Steps
 1. Create directory to store incoming data
 2. Create database
 3. Import database tables and rows
 4. Copy web-application to web-server document root
 5. Modify web-application configuration
 6. Modify the Apache configuration
 7. Initial login and setup

#### Create directory to store incoming data
Decide where the incoming data from OpenVDMv2 is going to be stored.  This location may be on the local system or on a remote storage array mounted to the local system via SMB, NFS, etc.  The default location is: `/mnt/vault/shoreside`.

#### Create database
In MySQL create an empty database for use by OpenVDMv2 - Port Office. The default database name is: OpenVDMv2-PortOffice.  If desired create a unique user account for accessing this database. 

#### Import database tables and rows
Use the OpenVDMv2-PortOffice_MySQLSchema.sql file to import the required tables and initial rows into the OpenVDMv2-PortOffice database.

#### Copy web-application to web-server document root
Copy the OpenVDMv2-PortOffice directory into the web-server document root.  On Xubuntu systems this is located at `/var/www/html/`.  Change the owner/group settings to match those used by Apache for that particular machine.  Also set the file permissions for `./OpenVDMv2-PortOffice/errorlog.html` to `777`.

#### Modify web-application configuration
You will need to modify the configuration of the web-application to match the system's configuration.  To do so edit the `./app/Core/Config.php` file.  Modify the values for the `site address`,`DB_NAME`, `DB_USER` and `DB_PASS` to match the current system.  The included Config.php has the site address as `127.0.0.1`.  This will need to be changed to the IP or hostname external users will use to access Port Office.

#### Modify the Apache configuration
OpenVDMv2 - Port Office uses the Apache rewrite module.  To enable mod_rewrite type: `a2enmod rewrite`.  If the module is not installed you will first need to run (on Xubuntu systems): `apt-get install libapache2-mod-rewrite`.

Next edit the `OpenVDMv2-PortOffice_Apache2.conf` such that the directory locations for OpenVDMv2-PortOffice and the storage location for incoming files match the choices made ealier.

Next copy the modified `OpenVDMv2-PortOffice_Apache2.conf` file into the Apache "sites-available" folder located (on Xubuntu systems) at: '/etc/apache2/' and enable the site with the command: `a2ensite OpenVDMv2-PortOffice_Apache2`.  Optionally the 2 directory directives and alias can be copied into the default apache config for the machine.

Restart Apache by typing:`sudo service apache2 restart`

#### Initial login and setup
In a web-browser (Firefox or Chrome, not IE at this time) go to: `http://<the server's IP/DNS Name>/OpenVDMv2-PortOffice`

If everything was configured correctly, the OpenVDMv2 - Port Office login screen should appear.  The default login is admin/demo.

The default login should be changed to something unique.  To do this goto the "Users" tab and edit the admin account.

Finally you will need to tell OpenVDMv2 - Port Office where to look for incoming data.  To do this goto the "System" tab and edit the "Filesystem Directory containing cruise data".

If everything is configured correctly and data is available at that location, Port Office should automatically detect the availble criuses and begin displaying dataset.
