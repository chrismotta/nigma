#!/bin/bash
cd /var/www/html/tmp/
ret=$(git pull origin providers)
echo $ret
cd
echo $(date) >> git_status.log
echo $ret    >> git_status.log
echo ''      >> git_status.log
