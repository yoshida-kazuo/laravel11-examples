ARG APP_BUILD_IMAGE

FROM ${APP_BUILD_IMAGE}

ARG APP_BUILD_ENV
ARG APP_NODE_VERSION
ARG APP_GROUP
ARG APP_GROUP_ID
ARG APP_USER
ENV APP_USER=${APP_USER}
ARG APP_USER_ID
ARG APP_SERVER_PATH
ENV APP_SERVER_PATH=${APP_SERVER_PATH}
ARG APP_SERVER_DOCUMENT_PATH
ENV APP_SERVER_DOCUMENT_PATH=${APP_SERVER_DOCUMENT_PATH}
ARG APP_PHP_MEMORY_LIMIT
ENV APP_PHP_MEMORY_LIMIT=${APP_PHP_MEMORY_LIMIT}
ARG APP_XDEBUG_CLIENT_HOST
ENV APP_XDEBUG_CLIENT_HOST=${APP_XDEBUG_CLIENT_HOST}
ARG APP_XDEBUG_CLIENT_PORT
ENV APP_XDEBUG_CLIENT_PORT=${APP_XDEBUG_CLIENT_PORT}
ARG APP_XDEBUG_IDEKEY
ENV APP_XDEBUG_IDEKEY=${APP_XDEBUG_IDEKEY}
ARG APP_HOST_NAME
ENV APP_HOST_NAME=${APP_HOST_NAME}

RUN apt update && \
        apt upgrade -y && \
        apt install -y nginx \
                bc \
                libfreetype6-dev \
                zip \
                libzip-dev \
                libz-dev \
                libavif-dev \
                libxpm-dev \
                libjpeg-dev \
                libmcrypt-dev \
                imagemagick \
                libmagickwand-dev \
                libmagickcore-dev \
                supervisor \
                poppler-utils \
                python3-venv \
                python3-pip \
                ffmpeg \
                libsm6 \
                libxext6 \
                gettext-base \
                pdftk \
                cron \
                jq \
                gnupg \
                apt-transport-https \
                lsb-release && \
        apt install -y fonts-arphic-ukai \
                fonts-arphic-uming \
                fonts-ipafont-mincho \
                fonts-ipafont-gothic \
                fonts-unfonts-core \
                fonts-liberation \
                fonts-dejavu \
                fonts-texgyre \
                fonts-noto-cjk-extra \
                fonts-takao \
                swig

RUN mkdir -p /usr/src/php/ext
WORKDIR /usr/src/php/ext

# TODO: Add Imagick installation once it fully supports PHP 8.4.
# Imagick currently has compatibility issues with PHP 8.4, so it will be added
# after a stable version with PHP 8.4 support is released.
RUN pecl install igbinary-3.2.16
RUN pecl install redis-6.1.0 --enable-redis-igbinary --enable-redis-lzf
RUN pecl install xdebug-3.4.0
RUN docker-php-ext-configure gd --with-avif --with-jpeg --with-xpm --with-webp
RUN docker-php-ext-install pdo_mysql opcache zip gd
RUN docker-php-ext-enable igbinary redis xdebug

RUN find /etc -type f -path "*/ImageMagick-*" -name "policy.xml" -exec sed -i.orig 's/policy domain="coder" rights="none" pattern="PDF"/policy domain="coder" rights="read|write" pattern="PDF"/g' {} + && \
        find /etc -type f -path "*/ImageMagick-*" -name "policy.xml" -exec sed -i.orig 's/<policy domain="resource" name="memory" value="256MiB"\/>/<policy domain="resource" name="memory" value="1GiB"\/>/g' {} + && \
        find /etc -type f -path "*/ImageMagick-*" -name "policy.xml" -exec sed -i.orig 's/<policy domain="resource" name="map" value="512MiB"\/>/<policy domain="resource" name="map" value="2GiB"\/>/g' {} +

WORKDIR /tmp
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
        curl -L https://raw.githubusercontent.com/tj/n/master/bin/n -o n && \
        bash n ${APP_NODE_VERSION} && \
        npm install npm@latest -g && \
        rm -rf /tmp/*

RUN groupadd -g ${APP_GROUP_ID} ${APP_GROUP} && \
        useradd -s /bin/false -u ${APP_USER_ID} -g ${APP_GROUP_ID} -M ${APP_USER}
RUN mkdir -p /var/www && \
        chown -R ${APP_USER}:${APP_GROUP} /var/www && \
        chown -R ${APP_USER}:${APP_GROUP} ${APP_SERVER_PATH}

WORKDIR /etc/cron.d
RUN echo "* * * * * cd ${APP_SERVER_PATH} && /usr/local/bin/php -d memory_limit=-1 artisan schedule:run" > laravel && \
        chmod 0644 ./laravel && \
        crontab -u ${APP_USER} laravel
WORKDIR /tmp
COPY etc/php/www.conf /tmp/www.conf.template
RUN envsubst < /tmp/www.conf.template > /usr/local/etc/php-fpm.d/www.conf
COPY etc/nginx/nginx.conf /tmp/nginx.conf.template
RUN envsubst '$$APP_USER $$APP_GROUP $$APP_HOST_NAME $$APP_SERVER_DOCUMENT_PATH' < /tmp/nginx.conf.template > /etc/nginx/nginx.conf
COPY etc/php/app.ini /tmp/app.ini.template
RUN envsubst < /tmp/app.ini.template > /usr/local/etc/php/conf.d/app.ini
COPY etc/supervisor/conf.d/worker.conf /tmp/worker.conf.template
RUN envsubst < /tmp/worker.conf.template > /etc/supervisor/conf.d/worker.conf
RUN rm -rf /tmp/*

WORKDIR ${APP_SERVER_PATH}

CMD ["/usr/bin/supervisord", "-n"]
