FROM phpdockerio/php71-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.1-cli php7.1-common php7.1-curl php7.1-pdo php7.1-mysql php7.1-bcmath php7.1-bz2 php7.1-imap php-imagick php7.1-intl php7.1-mbstring cron vim \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN service cron start

WORKDIR "/application"