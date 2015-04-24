#!/bin/bash

ROOT=$(pwd)

mkdir -p htmldev/{images,style,sass,javascript}

cp vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/.bowerrc htmldev/.bowerrc
cp vendor/zicht/htmldev-bundle/src/Zicht/Bundle/HtmldevBundle/Resources/views/_index.html.twig htmldev/_index.html.twig