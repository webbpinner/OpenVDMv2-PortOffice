#!/bin/bash

mkdir -p ../backup/usr/local/etc/openvdm
cp /usr/local/etc/openvdm/datadashboard.yaml ../backup/usr/local/etc/openvdm/
cp /usr/local/etc/openvdm/openvdm.yaml ../backup/usr/local/etc/openvdm/

mkdir -p ../backup/var/www/OpenVDMv2-PortOffice-VehicleEd/app/Core
cp /var/www/OpenVDMv2-PortOffice/.htaccess ../backup/var/www/OpenVDMv2-PortOffice/
cp /var/www/OpenVDMv2-PortOffice/app/Core/Config.php ../backup/var/www/OpenVDMv2-PortOffice/app/Core/

