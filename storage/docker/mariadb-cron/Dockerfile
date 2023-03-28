FROM alpine:3.11

ARG MYSQL_BACKUP
ARG MYSQL_DATABASE
ARG MYSQL_PASSWORD
ARG MYSQL_SOCKET
ARG MYSQL_USER

# Upload Cron(s)
COPY ./periodic /etc/periodic

# Update Package List
RUN apk update \
    # Upgrade Existing Extensions
    && apk upgrade \
    # Install Dependencies
    && apk add --no-cache \
        docker \
        gettext \
        moreutils \
    # Substitute Variables
    && sh -c " \
        envsubst '\$MYSQL_BACKUP \$MYSQL_DATABASE \$MYSQL_PASSWORD \$MYSQL_SOCKET \$MYSQL_USER' < /etc/periodic/5min/mariadb-dump | sponge /etc/periodic/5min/mariadb-dump \
    " \
    # Cron(s) Must Be Executable
    && chmod -R +x /etc/periodic \
    # Existing List Includes [15min, hourly, daily, weekly, monthly]
    && crontab -l | { cat; \
        echo '*/5    *    *    *    *    run-parts /etc/periodic/5min'; \
    } | crontab -

CMD ["crond", "-f", "-l", "8"]
