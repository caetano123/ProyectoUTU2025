#!/bin/bash

mkdir -p /run/php-fpm

php-fpm -D

httpd -DFOREGROUND
