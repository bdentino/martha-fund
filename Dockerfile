FROM wordpress

EXPOSE 80

ADD ./wp-content/themes/martha /var/www/html/wp-content/themes/martha
