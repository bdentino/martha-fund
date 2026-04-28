FROM wordpress:6.9.4-php8.4-apache

EXPOSE 80

ADD ./v2/wp-content/themes/martha /var/www/html/wp-content/themes/martha
