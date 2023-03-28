FROM mariadb:10.5

ARG MYSQL_SOCKET
ARG SOCKET_DIR

# Load Configuration
COPY ./conf.d /etc/mysql/conf.d

# Update Package List
RUN apt-get update \
    # Upgrade Existing Extensions
    && apt-get upgrade -y \
    # Install Dependencies
    && apt-get install -y \
        gettext \
        moreutils \
    # MySQL Socket Creation Fails If
    # - Incorrect Permissions Are Used
    # - Directory Doesn't Exist
    # - ID's Found In Mariadb Dockerfile
    && install -g mysql -o mysql -d ${SOCKET_DIR} \
    # Substitute Variables
    && sh -c " \
        envsubst '\$MYSQL_SOCKET' < /etc/mysql/conf.d/socket.cnf | sponge /etc/mysql/conf.d/socket.cnf \
    "

CMD ["mysqld"]
