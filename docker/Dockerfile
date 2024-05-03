FROM rrcfesc/lamp:8.2

LABEL maintainer="rrcfesc@gmail.com"

ENV IDEKEY "PHPSTORM"
ENV REMOTEPORT "9000"
ARG USER_ID
ARG GROUP_ID

COPY docker/apache/apache.conf /etc/apache2/sites-available/000-default.conf

RUN pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug \
    && echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.start_with_request = yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.max_nesting_level = 1000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey = ${IDEKEY}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.client_port = 9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.discover_client_host = on' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.remote_handler = dbgp' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.output_dir = '/var/www/html'" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.dump_globals = on' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.dump.SERVER = REQUEST_URI' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.show_local_vars = on' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.cli_color = 1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && chmod 666 /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && \
      echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | tee /etc/apt/sources.list.d/ngrok.list && \
      apt update && apt install ngrok
RUN curl  -o setup.deb.sh 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh'&& chmod +x setup.deb.sh && ./setup.deb.sh && rm -f setup.deb.sh
RUN apt install symfony-cli -y
RUN usermod --non-unique --uid ${USER_ID} www-data \
    && groupmod --non-unique --gid ${GROUP_ID} www-data
RUN chown www-data:www-data /var/www

EXPOSE 80 443