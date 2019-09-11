#!/bin/sh
pName='sof'

OUTPUT="$(ls -t mon*5\-*\-*\_*_*.bak.tgz | head -1)"
if [ -f $OUTPUT ]; then
     FILE_BACK="rtt_$(echo $OUTPUT | cut -c7-28)tgz"
     mv "${OUTPUT}"  "${FILE_BACK}"
fi

OUTPUT="$(ls -t mon*0\-*\-*\_*_*.bak.tgz | head -1)"
if [ -f $OUTPUT ]; then
     FILE_BACK="rtt_$(echo $OUTPUT | cut -c7-28)tgz"
     mv "${OUTPUT}"  "${FILE_BACK}"
fi

THE_DATE=$(date +"%d-%m-%Y_%H_%M")
/usr/bin/mongodump --db=${pName} --out=./
tar -czvf mongo_${pName}_${THE_DATE}.bak.tgz ./${pName}/
rm -rf ./${pName}
find ./mongo_*.bak.* -mtime +15 -exec rm {} \;
