#
# Copyright (C) 2016 Entware
#
# This is free software, licensed under the GNU General Public License v2.
# See /LICENSE for more information.
#

include $(TOPDIR)/rules.mk

PKG_NAME:=ext-ui-lighttpd-7
PKG_VERSION:=0.1
PKG_RELEASE:=2

include $(INCLUDE_DIR)/package.mk

define Package/ext-ui-lighttpd-7
 SECTION:=UI
 CATEGORY:=keendev
 DEPENDS:=+curl +lighttpd +lighttpd-mod-access +lighttpd-mod-accesslog \
	+lighttpd-mod-auth +lighttpd-mod-authn_file +lighttpd-mod-cgi \
	+lighttpd-mod-fastcgi +lighttpd-mod-redirect +lighttpd-mod-rewrite \
	+ndmq +php7-cli +php7-fastcgi +php7-mod-curl +php7-mod-dom \
	+php7-mod-exif +php7-mod-gd +php7-mod-json +php7-mod-mbstring \
	+php7-mod-session +php7-mod-simplexml +php7-mod-xml +php7-mod-xmlreader \
	+php7-mod-xmlwriter +php7-mod-zip +vnstat +shellinabox
 TITLE:=Easy installation script for extended web UI (php7*)
endef

define Package/ext-ui-lighttpd-7/description
 PHP (v.7*) version extended web user interface (ext-UI) for keenetic routers with NDMSv2
endef

define Build/Prepare
endef

define Build/Configure
endef

define Build/Compile
endef

define Package/ext-ui-lighttpd-7/install
	$(INSTALL_DIR) $(1)/opt/root
	$(CP) ./files/ext-ui-l.tar.gz $(1)/opt/root
endef

define Package/ext-ui-lighttpd-7/postinst
#!/bin/sh

logger "Welcome to Extended GUI installer"

#logger "Creating system backup..."
#tar cvzf /opt/backup-`date "+%Y-%m-%d"`.tar.gz -C /opt/ etc share var

cd /opt/root
tar -xzf ext-ui-l.tar.gz
cp -rf ./opt/* /opt/
rm -rf /opt/root/opt
rm -rf /opt/root/ext-ui-l.tar.gz

logger "Executing post-installation script..."
vnstat -u -i br0
vnstat -u -i eth2
vnstat -u -i eth2.1
vnstat -u -i eth2.2
vnstat -u -i ppp0
vnstat -u -i ra0

echo -n -e "\n Done! Pay your attention! The web-interfase login name \n\
    is root! Extended web UI will be available at following URL:\n\n\
    http://<ip_address_of_router>:88 \n\n\
    Do you wish to start extended web UI? [y]: "
read wish_to_start
[ -z "$$wish_to_start" ] && wish_to_start=y
if [ "$$wish_to_start" == "y" ]
then
    /opt/etc/init.d/S32vnstat start
    /opt/etc/init.d/S80lighttpd start
    /opt/etc/init.d/S88shellinaboxd start
fi
endef

$(eval $(call BuildPackage,ext-ui-lighttpd-7))
