RUN printf "\n" | pecl install \
        mongo && \
    echo "extension=mongo.so" >> /usr/local/etc/php/conf.d/php.ini
