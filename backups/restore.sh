#!/bin/sh
rm -rf ./minbar/
OUTPUT="$(ls -t *.tgz | head -1)"
echo "${OUTPUT}"
tar -xzvf "${OUTPUT}" ./
/usr/bin/mongo minbar --eval "db.dropDatabase();"
/usr/bin/mongorestore --db=minbar ./minbar --noIndexRestore
../yii indexer/create-index
