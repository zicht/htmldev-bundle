# Htmldev bundle

An environment to create living styleguides.

## Install

1. Require via composer.

```shell script
composer require zicht/htmldev-bundle
```

2. Configure routing

Add the following YML to your app's route configuration.

```yaml
htmldev:
    resource: "@ZichtHtmldevBundle/Resources/config/routing.yml"
```

**Note**: make sure the above configuration *above* any routing containing the /{locale} (or simular) pattern.

## Usage

To include files from the `htmldev` folder, use the `@htmldev` directive.

```twig
{% extends '@htmldev/file.html.twig' %}
```

To include files from the bundle, use the bundle direcyory:

```twig
{% import '@ZichtHtmldevBundle/macros/components.html.twig' as ui %}
  
{{ ui.component('cards/news, { title: 'Hodor' }) }}
```

To validate twig `_context` variables:

```twig
{% if app.debug %}
    {#
       Validate the provided template _context
       For rules, see: https://github.com/rakit/validation#available-rules
    #}
    {{ _context|validate_context({
        required_number: 'required|numeric',
        required_boolean: 'required|in:1,0',
        optional_string: 'in:foo,bar',
    }) }}
{% endif %}
```

## Maintainer

* Robert van Kempen ([@DoNomal](https://github.com/DoNormal))
