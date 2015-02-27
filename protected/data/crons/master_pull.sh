#!/bin/bash
cd /storage/www/html/app/
ret=$(git pull origin master)
echo $ret
cd
echo $(date) >> git_status.log
echo $ret    >> git_status.log
echo ''      >> git_status.log
