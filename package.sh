#!/usr/bin/env bash
cd $(dirname $0)
./composer.phar install --no-dev --prefer-dist >/dev/null 2>&1
./composer.phar dump >/dev/null 2>&1
rm slack.phar >/dev/null 2>&1
./pakket.phar build . slack.phar
chmod +x slack.phar
./composer.phar install >/dev/null 2>&1