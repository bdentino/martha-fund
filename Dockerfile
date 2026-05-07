FROM wordpress:6.9.4-php8.4-apache

EXPOSE 80

COPY ./v2/wp-content/themes/martha /var/www/html/custom-themes/martha
RUN chown -R www-data:www-data /var/www/html/custom-themes

COPY ./v2/docker-entrypoint.sh /usr/local/bin/martha-entrypoint.sh
RUN chmod +x /usr/local/bin/martha-entrypoint.sh

ENTRYPOINT ["martha-entrypoint.sh"]
CMD ["apache2-foreground"]
