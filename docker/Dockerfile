FROM coderstephen/php7:latest

# install nodejs 6
RUN curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
RUN apt-get install -y nodejs

# mbstring is required
RUN cd /usr/local/src/php/
RUN ./configure --prefix=/usr/local/php70 --with-config-file-path=/usr/local/php70 \
--with-config-file-scan-dir=/usr/local/php70/conf.d \
--with-apxs2=/usr/bin/apxs2 --with-libdir=/lib/x86_64-linux-gnu \
--enable-fpm --without-pear --with-openssl --with-curl \
--enable-mbstring --enable-zip

RUN make install

# checkout the source from respond cms
RUN cd /var/www/
RUN rm -rf html
RUN git clone https://github.com/madoublet/respond6.git .

# create writable required directories
RUN mkdir -p public/sites
RUN mkdir -p resources/sites
RUN chown -R www-data public/sites
RUN chown -R www-data resources/sites
RUN ln -s /var/www/public html
RUN ln -s /var/www/node_modules public/node_modules

RUN cp .env.example .env

# install required modules
RUN npm install
RUN composer install


# update apache config
sed  "s/var\\/www/var\\/www\\/public/" /etc/apache2/sites-enabled/000-default.conf

apache2ctl restart

EXPOSE 80

#CMD ["/run.sh"]
