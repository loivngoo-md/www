FROM otezvikentiy/php7.2-fpm:0.0.11

RUN apt-get update && apt-get install -y git-core \
   && apt-get -y autoremove && apt-get clean all && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer create-project --keep-vcs topthink/think tp5

RUN sed -i 's,https://tajs.qq.com,#,' /var/www/html/tp5/application/index/controller/Index.php \
  && sed -i 's,https://e.topthink.com,#,' /var/www/html/tp5/application/index/controller/Index.php \
  && cp -r /var/www/html/tp5 /var/www/tp5

VOLUME ["/var/www/html/tp5/application"]

COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD ["/run.sh"]
RUN sed -i 's,DocumentRoot /var/www/html,DocumentRoot /var/www/html/tp5/public,' /etc/apache2/sites-enabled/000-default.conf