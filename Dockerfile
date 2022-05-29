FROM php:8.0-fpm

WORKDIR /var/www

ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libssl-dev \
    cron \
    supervisor

# Install extensions
RUN docker-php-ext-install pdo_mysql
RUN apt-get install -y \
        libonig-dev \
    && docker-php-ext-install iconv mbstring
RUN apt-get install -y \
        libzip-dev \
        zlib1g-dev \
    && docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install nodejs
RUN apt-get update &&\
    apt-get install -y --no-install-recommends gnupg &&\
    curl -sL https://deb.nodesource.com/setup_16.x | bash - &&\
    apt-get update &&\
    apt-get install -y --no-install-recommends nodejs &&\
    npm config set registry https://registry.npm.taobao.org --global &&\
    npm install --global gulp-cli

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# create folder config supervisor
RUN mkdir -p /var/log/supervisor && mkdir -p /etc/supervisor/conf.d

# Permission
RUN chown -R www:www /var/www
RUN chown -R www:www /var/log/supervisor
RUN chown -R www:www /etc/supervisor/conf.d

# Copy file crontab
COPY crontab-schedule-run /crontab-schedule-run
RUN chmod -R 777 /crontab-schedule-run

# Apply cron job
RUN crontab -u www /crontab-schedule-run
RUN chmod u+s /usr/sbin/cron
RUN usermod -s /bin/bash www

# Run command
COPY entrypoint.sh /entrypoint.sh
RUN ["chmod", "+x", "/entrypoint.sh"]

# Change current user to www
USER www

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
