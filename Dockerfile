FROM laravelsail/php82-composer:latest

RUN apt-get update \
    && apt-get install -y libicu-dev libsqlite3-dev \
    && docker-php-ext-install intl pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
