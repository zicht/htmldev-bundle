#!/bin/bash

ROOT=$(pwd)
HTMLDEV=htmldev
RESOURCES=vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources
CONFIG=app/config/bundles

if [ ! -d $HTMLDEV ]; then
    mkdir -p $HTMLDEV/{images,style,sass,javascript}
    cp $RESOURCES/views/_index.html.twig $HTMLDEV/_index.html.twig
fi

if [ ! -f $HTMLDEV/.bowerrc ]; then
    cp $RESOURCES/.bowerrc $HTMLDEV/.bowerrc
fi

if [ ! -f $HTMLDEV/bower.json ]; then
    cp $RESOURCES/bower.json $HTMLDEV/bower.json
fi

if [ ! -f $CONFIG/zicht_htmldev.yml ]; then
    cp $RESOURCES/config/zicht_htmldev.yml $CONFIG/zicht_htmldev.yml
fi