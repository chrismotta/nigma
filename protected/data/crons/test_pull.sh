#!/bin/bash
cd /storage/www/html/test/
ret=$(git pull origin test)
echo $ret
#cd
#echo $(date) >> git_status.log
#echo $ret    >> git_status.log
#echo ''      >> git_status.log
