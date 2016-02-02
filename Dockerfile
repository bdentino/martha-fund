FROM wordpress

EXPOSE 80

ADD ./wp-content/themes/martha /var/www/html/wp-content/themes/martha

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
