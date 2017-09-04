#!/bin/bash

rsync -avi ../var/www/OpenVDMv2-PortOffice /var/www/
cp /var/www/OpenVDMv2-PortOffice/.htaccess.dist /var/www/OpenVDMv2-PortOffice/.htaccess
cp /var/www/OpenVDMv2-PortOffice/app/Core/Config.php.dist /var/www/OpenVDMv2-PortOffice/app/Core/Config.php
chmod 777 /var/www/OpenVDMv2-PortOffice/errorlog.html

rsync -avi ../usr/local/etc/openvdm/* /usr/local/etc/openvdm/
cp /usr/local/etc/openvdm/datadashboard.yaml.dist /usr/local/etc/openvdm/datadashboard.yaml
cp /usr/local/etc/openvdm/openvdm.yaml.dist /usr/local/etc/openvdm/openvdm.yaml


