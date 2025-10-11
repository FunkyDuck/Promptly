# STEP 1 : Choose php image
FROM php:8.4-cli-alpine

# STEP 2 : Install php & system dependance
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS mariadb-dev && \
    docker-php-ext-install pdo pdo_mysql sockets && \
    apk del .build-deps && \
    apk add --no-cache git zip unzip

# STEP 3 : Install  Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# STEP 4 : Prepare env
WORKDIR /app

# STEP 5 : Install dependancies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# STEP 6 : Copy bot source
COPY . .

# STEP 7 : Launch bot
CMD [ "php", "bot.php" ] 