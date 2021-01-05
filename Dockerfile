#first
ARG PHP_EXTENSIONS="pdo_mysql zip"
FROM thecodingmachine/php:7.3-v2-slim-apache as php_base
ENV TEMPLATE_PHP_INI=production
COPY --chown=docker:docker . /var/www/html
RUN composer update
RUN composer install --quiet --optimize-autoloader --no-dev

#second
# FROM node:10 as node_dependencies
# WORKDIR /var/www/html
# ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=false
# COPY --from=php_base /var/www/html /var/www/html
# RUN ls -al
# RUN npm set progress=false && \
#     npm config set depth 0 && \
#     npm install sass sass-loader && \
#     # npm install --save /var/www/html/resources/js/app.js && \
#     npm install && \
#     npm run prod && \
#     rm -rf node_modules

#third
# FROM php_base
# COPY --from=node_dependencies --chown=docker:docker /var/www/html/ /var/www/html