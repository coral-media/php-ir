FROM php:8.2-cli-bookworm

# System dependencies
RUN apt-get update && apt-get install -y \
    bash \
    curl \
    wget \
    git \
    unzip \
    nano \
    pkg-config \
    build-essential \
    autoconf \
    re2c \
    python3 \
    python3-pip \
    libicu-dev \
    libgmp-dev \
    libgettextpo-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-configure gd \
      --with-freetype \
      --with-jpeg \
      --with-webp && \
    docker-php-ext-install -j$(nproc) \
      gd \
      intl \
      zip \
      gmp \
      bcmath \
      gettext \
      opcache \
      exif

# Install APCu
RUN pecl install apcu && \
    docker-php-ext-enable apcu && \
    echo "apc.enable_cli=1" > /usr/local/etc/php/conf.d/apcu.ini

# Install zephir_parser
RUN pecl install zephir_parser && \
    docker-php-ext-enable zephir_parser

# Install Xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini

# PHP custom config
RUN echo "post_max_size = 150M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "upload_max_filesize = 150M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini

# Install Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/usr/local/composer

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

ENV PATH="/usr/local/composer/vendor/bin:${PATH}"

RUN set -eux; \
    ZEPHIR_TAG=$(git ls-remote https://github.com/zephir-lang/zephir '0.18.*' \
        | sort -t/ -k3 -Vr \
        | head -n1 \
        | awk -F/ '{print $NF}'); \
    git clone --depth 1 -b "$ZEPHIR_TAG" https://github.com/zephir-lang/zephir /opt/zephir; \
    cd /opt/zephir; \
    chmod a+x zephir; \
    composer install --no-dev --prefer-dist;

ENV PATH="/opt/zephir:${PATH}"

# Optional Xdebug helper (Alpine-compatible)
RUN echo 'alias xphp="export PHP_IDE_CONFIG=\"serverName=api.localhost\"; XDEBUG_SESSION=PHPSTORM php -dxdebug.start_with_request=yes"' >> /root/.bashrc

RUN mkdir -p /opt/extensions
WORKDIR /opt/extensions

CMD ["sleep", "infinity"]