#!/usr/bin/env bash
source $(dirname $(readlink -f $0))/script_begin.sh
wsuSymfony console DownloadDomains "$@"
source "${SCRIPT_DIR}script_end.sh"
