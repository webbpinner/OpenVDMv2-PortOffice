#!/bin/bash

rsync -avi /usr/local/etc/openvdm/openvdm.yaml.dist ~/OpenVDMv2-PortOffice/usr/local/etc/openvdm
rsync -avi /usr/local/etc/openvdm/datadashboard.yaml.dist ~/OpenVDMv2-PortOffice/usr/local/etc/openvdm
rsync -avi --exclude="errorlog.html" --exclude=".htaccess" --exclude="Config.php" /var/www/OpenVDMv2-PortOffice ~/OpenVDMv2-PortOffice/var/www/

