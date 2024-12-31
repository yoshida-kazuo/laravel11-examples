ARG APP_FRONTEND_BUILD_IMAGE

FROM ${APP_FRONTEND_BUILD_IMAGE}

ARG APP_USER
ARG APP_USER_ID
ARG APP_GROUP
ARG APP_GROUP_ID
ARG APP_FRONTEND_SERVER_PATH

ENV APP_USER=${APP_USER}
ENV APP_GROUP=${APP_GROUP}
ENV NODE_PATH=/home/node/app/node_modules
ENV PATH="${APP_FRONTEND_SERVER_PATH}/node_modules/.bin:$PATH"

RUN if ! getent group | awk -F: '{print $3}' | grep -qw ${APP_GROUP_ID}; then \
        groupadd -g ${APP_GROUP_ID} ${APP_GROUP}; \
    fi

RUN if ! getent passwd | awk -F: '{print $3}' | grep -qw ${APP_USER_ID}; then \
        useradd -s /bin/false -u ${APP_USER_ID} -g ${APP_GROUP_ID} -d ${APP_FRONTEND_SERVER_PATH} ${APP_USER}; \
    fi

WORKDIR ${APP_FRONTEND_SERVER_PATH}
RUN chown -R ${APP_USER}:${APP_GROUP} ${APP_FRONTEND_SERVER_PATH}
