FROM php:7.2-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends gnupg apt-transport-https unixodbc-dev && \
    apt-get purge -y --auto-remove && \
    rm -rf /var/lib/apt/lists/*

# install odbc fro sql server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl https://packages.microsoft.com/config/debian/9/prod.list > /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && \
    ACCEPT_EULA=Y apt-get install -y --no-install-recommends msodbcsql17 && \
    apt-get purge -y --auto-remove && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install sqlsrv pdo_sqlsrv > /dev/null && \
        rm -rf /tmp/pear ~/.pearrc

# create a directory for database config
RUN mkdir -p -m 777 /var/www/config/

# copy configuration files
#COPY conf/mobile.conf /etc/apache2/sites-enabled/000-default.conf
COPY conf/mobile.ini /usr/local/etc/php/conf.d/
COPY conf/database.ini /var/www/config/

# set up work dir
WORKDIR /var/www/html/

# create a directory for logs
RUN mkdir -p -m 777 /var/log/mobile/

# copy source code
COPY src/ /var/www/html/

# expose port 80
EXPOSE 80

# start apache automatically
CMD ["apache2-foreground"]