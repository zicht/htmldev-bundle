#!/bin/bash

ROOT=$(pwd)
HTMLDEV=htmldev
RESOURCES=vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources
CONFIG=app/config/bundles

if [ ! -d $HTMLDEV ]; then
    mkdir -p $HTMLDEV/{images,style,sass,javascript}
    cp $RESOURCES/views/_index.html.twig $HTMLDEV/_index.html.twig
    cp $RESOURCES/views/_base.html.twig $HTMLDEV/_base.html.twig
fi

if [ ! -f $ROOT/.bowerrc ]; then
    cp $RESOURCES/.bowerrc $ROOT/.bowerrc
fi

if [ ! -f $ROOT/bower.json ]; then
    cp $RESOURCES/bower.json $ROOT/bower.json
fi

bower install

if [ ! -f $CONFIG/zicht_htmldev.yml ]; then
    cp $RESOURCES/config/zicht_htmldev.yml $CONFIG/zicht_htmldev.yml
fi