FROM php:8.3-fpm

# timezone
ENV TZ=Asia/Tokyo
# RUN echo "${TZ}" > /etc/timezone \
#     && dpkg-reconfigure -f noninteractive tzdata

# Install required libraries
RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    unzip \
    vim \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libfontconfig1 \
    libxrender1 \
    # Install required libraries
    libreoffice libreoffice-l10n-ja \
    # Install crond
    cron

# Install PHP extensions
RUN apt-get install -y libzip-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pdo_mysql mysqli exif
# Install zip extension
RUN docker-php-ext-install zip
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# Install xdebug; docker compose up -d --build
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# cron related files
RUN touch /var/log/cron.log && \
    chown www-data /var/log/cron.log && \
    chmod 644 /var/log/cron.log

USER www-data
RUN echo "* * * * * > /var/log/cron.log" | crontab -
RUN echo "* * * * * /usr/local/bin/php /src/artisan schedule:run >> /var/log/cron.log" | crontab -

USER root
# CMD ["cron", "-f"]

ENV NODE_VERSION=22.12.0
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version

WORKDIR /src
ADD . /src/storage
RUN chown -R www-data:www-data /src/storage

# Start the cron service
CMD ["sh", "-c", "cron && php-fpm"]