#!/bin/bash

CWD=`pwd`
export TMPL_PLUGIN_NAME='CAP_PLUGIN_TEMPLATE'
export WP_TESTS_DIR="${CWD}/wordpress-tests-lib"
export WP_CORE_DIR="${CWD}/wordpress/"

echo "Starting phpunit"

/usr/local/bin/phpunit
