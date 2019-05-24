#!/bin/sh
rm -rf ./sof/
OUTPUT="$(ls -t *.tgz | head -1)"
echo "${OUTPUT}"
tar -xzvf "${OUTPUT}" ./
/usr/bin/mongo sof --eval "db.dropDatabase();"
/usr/bin/mongorestore --db=sof ./sof --noIndexRestore
../yii indexer/create-index
