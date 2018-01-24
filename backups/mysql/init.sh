#!/bin/bash

# gdrive configuration
#http://olivermarshall.net/how-to-upload-a-file-to-google-drive-from-the-command-line/

# dir where script is
DIR="$(cd "$(dirname "$0")" && pwd -P)"

DATE=`date '+%Y-%m-%d-%H-%M-%S'`
STORAGE=$DIR/storage
FILE=$STORAGE/$DATE.sql
TARGZ=$FILE.tar.gz

#echo $DIR This is theddddddddddddddddddddddddddddddddddddddddddddd

# source .env file
source $DIR/../../.env;

# create file
docker exec $DB_CONTAINER /usr/bin/mysqldump -u $DB_ROOT_USER --password=$DB_ROOT_PASS $DB_DATABASE > $FILE

tar -cvzf $STORAGE/$DATE.sql.tar.gz $STORAGE/$DATE.sql

rm -f $FILE

/usr/local/bin/gdrive upload $TARGZ --parent $GDRIVE_DIR_BACKUP_ID

# delete files
for i in `ls -pt $STORAGE/*.sql.tar.gz | tail -n+100`;
    do
        if [ -f $i ] ; then
            rm $i
        fi
done;
