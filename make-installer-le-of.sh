#!/bin/sh

# Working dir should stay in feeds/keendev
SCRIPT_DIR=$(dirname $0)
ROOT_DIR=$SCRIPT_DIR/installer_root
BUILD_DIR=$SCRIPT_DIR/../../build_dir/target-mipsel_mips32r2_uClibc-*
INSTALLER=$SCRIPT_DIR/installer-keenle-of.tar.gz

[ -d $ROOT_DIR ] && rm -fr $ROOT_DIR
mkdir $ROOT_DIR

# Adding toolchain libraries
cp -r $BUILD_DIR/toolchain/ipkg-keenle/libc/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-keenle/libgcc/opt $ROOT_DIR
#
cp -r $BUILD_DIR/toolchain/ipkg-keenle/ldconfig/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-keenle/libssp/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-keenle/libpthread/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-keenle/librt/opt $ROOT_DIR
cp -r $BUILD_DIR/toolchain/ipkg-keenle/libstdcpp/opt $ROOT_DIR
#
cp -r $BUILD_DIR/dropbear-*/ipkg-keenle/dropbear/opt $ROOT_DIR
cp -r $BUILD_DIR/opt-ndmsv2-*/ipkg-keenle/opt-ndmsv2/opt $ROOT_DIR
cp -r $BUILD_DIR/ndmq-*/ipkg-keenle/ndmq/opt $ROOT_DIR
cp -r $BUILD_DIR/libndm-*/ipkg-keenle/libndm/opt $ROOT_DIR
cp -r $BUILD_DIR/findutils-*/ipkg-keenle/findutils/opt $ROOT_DIR
cp -r $BUILD_DIR/libncurses/ncurses-*/ipkg-keenle/terminfo/opt $ROOT_DIR
#
mv -f $ROOT_DIR/opt/etc/init.d/* $ROOT_DIR/opt/home
# Adding busybox
cp -r $BUILD_DIR/busybox-*/ipkg-install/opt $ROOT_DIR

# This script will seed some dots to terminal.
# Otherwise, f\w will kill installation after 8 seconds of silence
#cp $SCRIPT_DIR/dots.sh $ROOT_DIR/opt/bin
#chmod +x $ROOT_DIR/opt/bin/dots.sh

# Adding dummie SSH keys to avoid dropbear post-install timeout
mkdir -p $ROOT_DIR/opt/etc/dropbear
touch $ROOT_DIR/opt/etc/dropbear/dropbear_ecdsa_host_key
touch $ROOT_DIR/opt/etc/dropbear/dropbear_rsa_host_key

# Adding install script
mkdir -p $ROOT_DIR/opt/etc/init.d
cp $SCRIPT_DIR/doinstall-of $ROOT_DIR/opt/etc/init.d/doinstall
chmod +x $ROOT_DIR/opt/etc/init.d/doinstall

# Adding opkg&opkg.conf
cp -r $BUILD_DIR/linux-keenle/opkg-*/ipkg-keenle/opkg/opt $ROOT_DIR
cp -r $SCRIPT_DIR/opkg-keenle.conf $ROOT_DIR/opt/etc/opkg.conf
chmod 644 $ROOT_DIR/opt/etc/opkg.conf

# copy strip version
cp -fr $BUILD_DIR/busybox-*/ipkg-keenle/busybox/opt $ROOT_DIR

# Packing installer
[ -f $INSTALLER ] && rm $INSTALLER
tar -czf $INSTALLER -C $ROOT_DIR/opt --owner=root --group=root bin etc home lib root sbin share tmp usr var

# Removing temp folder
rm -fr $ROOT_DIR
