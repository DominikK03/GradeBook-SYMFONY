FROM php:8.3.8-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    libicu-dev \
    curl \
    gnupg \
    librabbitmq-dev \
    && docker-php-ext-install \
    zip \
    pdo \
    pdo_mysql \
    intl

RUN pecl install amqp \
    && docker-php-ext-enable amqp

RUN docker-php-ext-configure intl

RUN curl -sL https://deb.nodesource.com/setup_23.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y supervisor
COPY ./supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN mkdir -p /var/log/supervisor

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


WORKDIR /var/www


CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]