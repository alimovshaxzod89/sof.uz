#!/bin/sh
pName='sof'

rm -rf ./${pName}/
OUTPUT="$(ls -t *.tgz | head -1)"
echo "${OUTPUT}"
tar -xzvf "${OUTPUT}" ./
/usr/bin/mongo ${pName} --eval "db.dropDatabase();"
/usr/bin/mongorestore --db=${pName} ./${pName} --noIndexRestore
../yii indexer/create-index
