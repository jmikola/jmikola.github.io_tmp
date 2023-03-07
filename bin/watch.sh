#!/usr/bin/env bash

DIR=$(dirname $0)

cd $DIR/../

bundle exec jekyll serve -wl
