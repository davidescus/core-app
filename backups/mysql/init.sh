#!/bin/bash

# gdrive configuration
#http://olivermarshall.net/how-to-upload-a-file-to-google-drive-from-the-command-line/

DATE=`date '+%Y-%m-%d-%H-%M-%S'`
STORAGE=$PWD/storage
FILE=$STORAGE/$DATE.sql
TARGZ=$FILE.tar.gz

# source .env file
source ../../.env;

# create file
docker exec $DB_CONTAINER /usr/bin/mysqldump -u $DB_ROOT_USER --password=$DB_ROOT_PASS $DB_DATABASE > $FILE

tar -cvzf storage/$DATE.sql.tar.gz storage/$DATE.sql

rm -f $FILE

gdrive upload $TARGZ

# delete files
for i in `ls -pt $STORAGE/*.sql.tar.gz | tail -n+100`;
    do
        if [ -f $i ] ; then
            rm $i
        fi
done;
