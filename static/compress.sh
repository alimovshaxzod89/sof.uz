#!/usr/bin/env bash
FOLDER=/var/www/oila/static/crop/
sudo chmod 777 -R ./
for dir in `ls $FOLDER`;
do
    cd $FOLDER/$dir
    pwd
    for subdir in `ls $FOLDER/$dir`;
    do
      cd $FOLDER/$dir/$subdir
      pwd
      #jpegoptim -s -t -m80 *.jpg
      #jpegoptim -s -t -m80 *.jpeg
       for PHOTO in *.jpg
       do
          BASE=`basename $PHOTO`
          echo "$PHOTO"
          convert "$PHOTO" -sampling-factor 4:2:0 -strip -quality 75 -interlace JPEG -colorspace sRGB  "$PHOTO"
       done

       for PHOTO in *.jpeg
       do
          BASE=`basename $PHOTO`
          echo "$PHOTO"
          convert "$PHOTO" -sampling-factor 4:2:0 -strip -quality 75 -interlace JPEG -colorspace sRGB  "$PHOTO"
       done
    done
done