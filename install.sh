#!/bin/bash
if [ ! `php -m | grep ssh2` ]; then
    echo "ERROR: module 'ssh2' is not enabled in PHP configuration"
    exit
fi

ENV=env.php
if [ ! -f $ENV ]; then
    cp env.dist.php ev.php
    echo "File '$ENV' created, please edit to suit your configuration"
else
    echo "Edit '$ENV' to suit your configuration"
fi

if [ `command -v phpunit` ]; then
    echo "PHPUnit is installed, you can run tests by typing 'phpunit'"
else
    echo "WARNING: PHPUnit is probably not installed, please check and run test"
fi
