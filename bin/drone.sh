#!/bin/bash

export DRONE_SERVER=
export DRONE_TOKEN=

REPO=

drone secret add --image appleboy/drone-ssh --event pull_request ${REPO} DEPLOY_KEY @~/.ssh/id_rsa
drone secret add --image appleboy/drone-ssh --event pull_request ${REPO} DEPLOY_HOST
drone secret add --image appleboy/drone-ssh --event pull_request ${REPO} DEPLOY_USER
drone secret add --image appleboy/drone-ssh --event pull_request ${REPO} DEPLOY_SERVICE

drone secret add --image docker --event pull_request ${REPO} HUB_LOGIN
drone secret add --image docker --event pull_request ${REPO} HUB_PASSWORD

drone secret add --image docker --event pull_request ${REPO} GITHUB_AUTH_TOKEN

drone secret add --image alpine --event pull_request ${REPO} MD5_LIST "`md5sum $(find docker -type f | tr '\n' ' ')`"

drone sign ${REPO}

notify-send `basename "$0"` COMPLETE!
