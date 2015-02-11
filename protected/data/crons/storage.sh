#!/bin/bash
convStorage=$(curl http://kickadserver.mobi/convLog/storage)
clickStorage=$(curl http://kickadserver.mobi/clicksLog/storage)

cd
echo $(date)        >> storage_status.log
echo $convStorage   >> storage_status.log
echo $clickStorage  >> storage_status.log
echo ''             >> storage_status.log

echo $convStorage
echo $clickStorage
