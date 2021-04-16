FROM php:8.0.3-fpm

USER root

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update
RUN apt-get install -y sudo supervisor cron nginx mariadb-client gnupg gosu curl ca-certificates zip unzip git \
    libcap2-bin libpng-dev ffmpeg libfreetype6-dev libjpeg62-turbo-dev libpng-dev

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd
RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

RUN echo '* * * * *   root   cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1' >> /etc/crontab

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_15.x | bash - && apt-get install -y nodejs

RUN apt-get update
RUN apt-get -y autoremove
RUN apt-get clean
RUN rm -rf /var/lib/apt-get/lists/* /tmp/* /var/tmp/*

COPY ./docker/prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/prod/php.ini $PHP_INI_DIR/php.ini
COPY ./docker/prod/nginx.conf /etc/nginx/sites-enabled/default

RUN mkdir /var/www/.npm && chown -R www-data:www-data /var/www/.npm
RUN mkdir /var/www/.composer/ && chown -R www-data:www-data /var/www/.composer/

COPY --chown=www-data ./ ./

RUN sudo -u www-data composer install --quiet --no-dev --no-autoloader --no-interaction --no-progress
RUN sudo -u www-data npm install --quiet
RUN sudo -u www-data npm run prod --quiet

RUN sudo -u www-data mv .env.prod .env

EXPOSE 80
ENTRYPOINT ["/bin/bash", "/var/www/html/docker/prod/boot"]
CMD ["/usr/bin/supervisord"]
