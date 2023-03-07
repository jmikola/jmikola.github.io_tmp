#!/bin/bash

DIR=$(dirname $0)

cd $DIR/../

docker run -it --rm \
    --volume="$PWD:/srv/jekyll" \
    -p 4000:4000 jekyll/jekyll \
    jekyll serve
