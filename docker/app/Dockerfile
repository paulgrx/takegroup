FROM composer:2 AS composer
FROM node:25.6.0 AS node

FROM dunglas/frankenphp:1.11.1-builder-php8.5.2-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    cron \
    nano \
    supervisor \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    gd \
    pcntl \
    pdo \
    pdo_mysql \
    redis

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN echo "* * * * * cd /var/www && /usr/local/bin/php artisan schedule:run >> /var/log/schedule.log 2>&1" > /etc/cron.d/artisan-scheduler \
    && chmod 0644 /etc/cron.d/artisan-scheduler \
    && crontab /etc/cron.d/artisan-scheduler

WORKDIR /var/www

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
