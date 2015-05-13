#!/bin/bash
convStorage=$(curl http://tmlbox.co/convlog/storage)
clickStorage=$(curl http://tmlbox.co/clickslog/storage)

cd
echo $(date)        >> storage_status.log
echo $convStorage   >> storage_status.log
echo $clickStorage  >> storage_status.log
echo ''             >> storage_status.log

echo $convStorage
echo $clickStorage
