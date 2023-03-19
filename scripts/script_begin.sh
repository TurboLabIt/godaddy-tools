#!/usr/bin/env bash
# 🪄 Based on https://github.com/TurboLabIt/webstackup/blob/master/my-app-template/scripts/script_begin.sh

APP_NAME="godaddy-tools"
PROJECT_FRAMEWORK=symfony

## https://github.com/TurboLabIt/webstackup/blob/master/script/filesystem/script_begin_start.sh
source "/usr/local/turbolab.it/webstackup/script/filesystem/script_begin_start.sh" 

ZZ_CMD_SUFFIX=1

## Enviroment variables and checks
if [ "$APP_ENV" = "prod" ]; then
  EMOJI=🔧
fi
