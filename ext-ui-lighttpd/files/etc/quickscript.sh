#!/opt/bin/sh

tar cvzf /opt/backup-`date "+%Y-%m-%d_%H-%M"`.tar.gz /opt -C /opt/ .
