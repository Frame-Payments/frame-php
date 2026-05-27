FROM php:8.3-cli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN useradd --create-home --shell /bin/bash app

WORKDIR /app

COPY --chown=app:app . .

RUN composer update --no-dev --optimize-autoloader
RUN composer install --dev

RUN chown -R app:app /app

USER app

CMD ["./vendor/bin/phpunit"]
