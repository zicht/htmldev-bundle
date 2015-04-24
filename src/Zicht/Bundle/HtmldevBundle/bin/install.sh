#!/bin/bash

ROOT=$(pwd)

mkdir -p htmldev/{images,style,sass,javascript}

cp vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/.bowerrc htmldev/.bowerrc
cp vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/bower.json htmldev/bower.json
cp vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/views/_index.html.twig htmldev/_index.html.twig
cp vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/config/zicht_htmldev.yml app/config/bundles/zicht_htmldev.yml