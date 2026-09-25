FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends libpq-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_pgsql curl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/app
COPY docker/apache-site.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start-food-blog
RUN chmod +x /usr/local/bin/start-food-blog \
    && mkdir -p /tmp/php-sessions /var/www/app/public/uploads/profiles /var/www/app/public/uploads/menu \
    && chown -R www-data:www-data /tmp/php-sessions /var/www/app/public/uploads \
    && printf 'session.save_path=/tmp/php-sessions\nupload_max_filesize=2M\npost_max_size=3M\n' > /usr/local/etc/php/conf.d/food-blog.ini

WORKDIR /var/www/app
CMD ["start-food-blog"]
