#!/bin/bash

composer update

echo alias phpunit=\"php ${PWD}/vendor/bin/phpunit\" > ~/.bash_aliases