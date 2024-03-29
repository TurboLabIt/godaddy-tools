#!/usr/bin/env bash
# 🪄 Based on https://github.com/TurboLabIt/webstackup/blob/master/my-app-template/scripts/cleaner.sh

SCRIPT_NAME=cleaner
source $(dirname $(readlink -f $0))/script_begin.sh

fxHeader "🧹 ${SCRIPT_NAME}"
rootCheck

fxTitle "Deleting old CSV files..."
CSV_RETENTION_DAYS=20
find "${PROJECT_DIR}var/domains/"* -mtime +${CSV_RETENTION_DAYS} -name '*' -delete
fxOK

source ${SCRIPT_DIR}/script_end.sh
