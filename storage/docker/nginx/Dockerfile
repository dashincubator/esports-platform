FROM nginx:1.17.9-alpine

ARG NGINX_ERROR_403
ARG NGINX_ERROR_404
ARG PHP_PUBLIC_INDEX
ARG PHP_PUBLIC_DIR
ARG PHP_SOCKET

# Upload Configuration
COPY ./conf.d /etc/nginx/conf.d

# Update Package List
RUN apk update \
    # Upgrade Existing Extensions
    && apk upgrade \
    # Install Dependencies
    && apk add --no-cache \
        gettext \
        moreutils \
    # Setup For PHP Unix Socket Usage
    && addgroup nginx nobody \
    # Substitute Variables 
    && sh -c " \
        envsubst '\$NGINX_ERROR_403 \$NGINX_ERROR_404 \$PHP_PUBLIC_INDEX \$PHP_PUBLIC_DIR \$PHP_SOCKET' < /etc/nginx/conf.d/default.conf | sponge /etc/nginx/conf.d/default.conf \
        && envsubst '\$PHP_PUBLIC_INDEX \$PHP_PUBLIC_DIR \$PHP_SOCKET' < /etc/nginx/conf.d/php.conf | sponge /etc/nginx/conf.d/php.conf \
    "

CMD ["nginx", "-g", "daemon off;"]
