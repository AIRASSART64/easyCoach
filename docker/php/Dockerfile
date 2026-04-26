FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install \
        intl \
        pdo_mysql \
        zip \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:latest /usr/bin/composer/ /usr/bin/composer
WORKDIR /app
