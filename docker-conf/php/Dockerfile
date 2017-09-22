FROM php:7.1-fpm

#ARG HOST_USER_UID=1000
#ARG HOST_USER_GID=1000

# Create useer notroot
#RUN set -ex                                                 && \ 
#                                                               \ 
#    echo 'Creating notroot user and group from host'        && \ 
#    groupadd -g $HOST_USER_GID notroot                      && \ 
#    useradd -lm -u $HOST_USER_UID -g $HOST_USER_GID notroot

# Install Tools
RUN apt-get update && apt-get -y install build-essential \
        htop \
        libcurl3 \
        libcurl3-dev \
        librecode0 \
        libsqlite3-0 \
        libxml2 \
        curl \
        wget \
        python \
        vim \
        cron \
        git \
        unzip \
        autoconf \
        file \
        g++ \
        gcc \
        libc-dev \
        make \
        pkg-config \
        re2c \
        ca-certificates --no-install-recommends

# Install PHP 7 Extension
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libsqlite3-dev \
        libssl-dev \
        libcurl3-dev \
        libxml2-dev \
        libzzip-dev \
        libldap2-dev  \
        libicu-dev \
        libxslt-dev \
        libc-client-dev \
        libkrb5-dev \
    && docker-php-ext-install calendar bcmath iconv json mcrypt mbstring phar curl ftp intl pdo_mysql hash session simplexml tokenizer xml xmlrpc zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install gd imap

#COPY php.fpm.ini /etc/php7/fpm/php.ini
#COPY php.cli.ini /etc/php7/cli/php.ini

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

## Installs sendmail
#RUN apt-get update && apt-get install -q -y ssmtp mailutils && rm -rf /var/lib/apt/lists/*
#
## set up sendmail config, see http://linux.die.net/man/5/ssmtp.conf for options
#RUN echo "hostname=localhost.localdomain" > /etc/ssmtp/ssmtp.conf
#RUN echo "root=c.maessen@realskeptic.com" >> /etc/ssmtp/ssmtp.conf
#RUN echo "mailhub=maildev" >> /etc/ssmtp/ssmtp.conf
## The above 'maildev' is the name you used for the link command
## in your docker-compose file or docker link command.
## Docker automatically adds that name in the hosts file
## of the container you're linking MailDev to.
#
## Set up php sendmail config
#RUN echo "sendmail_path=sendmail -i -t" >> /usr/local/etc/php/conf.d/php-sendmail.ini
#
## Fully qualified domain name configuration for sendmail on localhost.
## Without this sendmail will not work.
## This must match the value for 'hostname' field that you set in ssmtp.conf.
#RUN echo "localhost localhost.localdomain" >> /etc/hosts


# Set user notroot
#USER notroot

# Set working dir
WORKDIR /root/projects/core-app/files

# Expose Ports & Volumes
EXPOSE 9000

CMD ["php-fpm", "-F", "-c", "/etc/php7/fpm"]

