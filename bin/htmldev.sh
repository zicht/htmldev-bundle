#!/bin/bash

ROOT=$(pwd)
HTMLDEV=htmldev
RESOURCES=vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources
CONFIG=app/config/bundles
BUNDLE_NAME=$1

if [ -z "$BUNDLE_NAME" ]; then
    echo 'please provide the bundle name'
    exit 1
fi

if [ ! -d $HTMLDEV ]; then
    mkdir -p $HTMLDEV/{components,images,javascript,src,macros,style,sass}
    cp $RESOURCES/views/_index.html.twig $HTMLDEV/_index.html.twig
    cp $RESOURCES/views/_base.html.twig $HTMLDEV/_base.html.twig
    cp $RESOURCES/views/macros/components.html.twig $HTMLDEV/macros/components.html.twig

    sed "s/@bundle@/$BUNDLE_NAME/g" $RESOURCES/system.conf.js > $HTMLDEV/javascript/system.conf.js
fi

if [ ! -f $HTMLDEV/browserslist ]; then
    cp $RESOURCES/browserslist $HTMLDEV/browserslist
fi

if [ ! -f $ROOT/package.json ]; then
    cp $RESOURCES/package.json $ROOT/package.json
fi

if [ ! -f $ROOT/.bowerrc ]; then
    cp $RESOURCES/.bowerrc $ROOT/.bowerrc
fi

if [ ! -f $ROOT/bower.json ]; then
    cp $RESOURCES/bower.json $ROOT/bower.json
fi

bower install
npm install

if [ ! -f $CONFIG/zicht_htmldev.yml ]; then
    cp $RESOURCES/config/zicht_htmldev.yml $CONFIG/zicht_htmldev.yml
fi
