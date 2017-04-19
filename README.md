# Htmldev bundle

An environment to create living styleguides.

## Install

1. Require via composer.

```
composer require zicht/htmldev-bundle
```

2. Configure routing

Add the following YML to your app's route configuration.

```
htmldev:
    resource: "@ZichtHtmldevBundle/Resources/config/routing.yml"
```

**Note**: make sure the above configuration *above* any routing containing the /{locale} (or simular) pattern.

## Usage

To include files from the `htmldev` folder, use the `@htmldev` directive.

```
{% extends '@htmldev/file.html.twig' %}
```

To include files from the bundle, use the bundle direcyory:

```
{% import '@ZichtHtmldevBundle/macros/components.html.twig' as ui %}
  
{{ ui.component('cards/news, { title: 'Hodor' }) }}
```

## Maintainer

* Robert van Kempen ([@DoNomal](https://github.com/DoNormal))
