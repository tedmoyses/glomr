FROM php:7.3-stretch

RUN apt-get update && apt-get install -y inotify-tools git \
	&& pecl install inotify \
	&& docker-php-ext-enable inotify \
	&& curl --show-error https://getcomposer.org/installer | php
	
RUN mkdir /app

COPY composer.json /app

WORKDIR /app

RUN php /composer.phar install --no-dev --optimize-autoloader

ENTRYPOINT ["php", "./glomr"]

COPY ./ /app


