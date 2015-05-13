#!/bin/bash
updateValidationStatus=$(curl http://tmlbox.co/finance/updateValidationStatus)

cd
echo $(date)                   >> updateValidationStatus.log
echo $updateValidationStatus   >> updateValidationStatus.log
echo ''                        >> updateValidationStatus.log

echo $updateValidationStatus
