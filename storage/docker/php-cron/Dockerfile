FROM alpine:3.11

ARG PHP_JOBS_DIR

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
           envsubst '\$PHP_JOBS_DIR' < /etc/periodic/1min/jobs  | sponge /etc/periodic/1min/jobs  \
        && envsubst '\$PHP_JOBS_DIR' < /etc/periodic/3min/jobs  | sponge /etc/periodic/3min/jobs  \
        && envsubst '\$PHP_JOBS_DIR' < /etc/periodic/5min/jobs  | sponge /etc/periodic/5min/jobs  \
        && envsubst '\$PHP_JOBS_DIR' < /etc/periodic/10min/jobs | sponge /etc/periodic/10min/jobs \
        && envsubst '\$PHP_JOBS_DIR' < /etc/periodic/15min/jobs | sponge /etc/periodic/15min/jobs \
    " \
    # Cron(s) Must Be Executable
    && chmod -R +x /etc/periodic \
    # Existing List Includes [15min, hourly, daily, weekly, monthly]
    && crontab -l | { cat; \
        echo '*     *   *   *   *   run-parts /etc/periodic/1min'; \
        echo '*/3   *   *   *   *   run-parts /etc/periodic/3min'; \
        echo '*/5   *   *   *   *   run-parts /etc/periodic/5min'; \
        echo '*/10  *   *   *   *   run-parts /etc/periodic/10min'; \
    } | crontab -

CMD ["crond", "-f", "-l", "8"]
