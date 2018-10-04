# Htmldev Bundle

Create living styleguides with Symfony and Twig.

---

- [Goals](#goals)
- [Install](#install)
- [How to use](#how-to-use)
   - [Setup](#setup)
   - [Adding stuff to the styleguide](#adding-stuff-to-the-styleguide)
      - [Components](#components)
      - [Pages](#pages)
      - [Rendering components in the styleguide](#rendering-components-in-the-styleguide)
      - [Rendering icons](#rendering-icons)
      - [Rendering colors](#rendering-colors)
   - [Using the styleguide in the project](#using-the-styleguide-in-the-project)
      - [Rendering components](#rendering-components)
      - [Rendering SVG files](#rendering-svg-files)
   - [Customising](#customising)
---

## Goals

- Provide a single visual source for the design of a project.
- Serve components and assets from the styleguide to prevent code duplication.
- Deliver a good developer experience (DX) ✨ that makes creating a good-looking styleguide very easy.

## Install

1. Require via composer.

```
composer require zicht/htmldev-bundle
```

## How to use

### Setup

1. Load the bundle into your AppKernel.

```php
new Zicht\Bundle\GridBundle\ZichtHtmldevBundle()
```

2. Configure routing.

Add the following Yaml to your app's route configuration.

```yaml
htmldev:
    resource: "@ZichtHtmldevBundle/Resources/config/routing.yml"
```

⚠️ if you combine this bundle with the [zicht/page-bundle](https://github.com/page-bundle), make sure this configuration is placed *above* any routing containing `/{locale}`.

3. Create a directory in your project from which the styleguide will be served. The default expects a `htmldev` directory in the root of your project.
   
4. Add a Twig template `_base.html.twig` to the `~/htmldev` created in the previous step. It should looke something like this:

```twig
{% extends 'ZichtHtmldevBundle:styleguide:index.html.twig' %}


{% block head %}
    <link rel="stylesheet" href="{{ asset('bundles/zichthtmldev/css/styleguide.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/main.css') }}">
    <script defer src="{{ asset('assets/main.min.js') }}"></script>
{% endblock %}
```

The first CSS file contains the design of the styleguide, while the files below it should be the CSS and script files of 
the project iteself.

5. Create a directory `data/styleguide` in the `~/htmldev` directory. Add a file `navigation.yml` containing at least
the following:

```yaml
-
    title: Components
    uri: /htmldev/components/buttons
```

6. Create a directory `pages/components` in the `~/htmldev` directory. Add a Twig template `buttons.html.twig`:

```twig
{% extends '@htmldev/_base.html.twig' %}
{% import 'ZichtHtmldevBundle:macros:components.html.twig' as ui %}


{% block head_title 'Buttons - Styleguide' %}


{% block component_intro %}
    <h2 class="c-copy--h2">Buttons</h2>
{% endblock %}
```

7. Go to the styleguide's URL at `/htmldev` (e.g. http://localhost/htmldev) and you should see a basic setup of the styleguide: 

![](docs/initial-setup.jpg)

### Adding stuff to the styleguide

#### Components

Components are simple Twig templates which represent a small peace of the design of a project, for example a button or a card.
The HtmldevBundle looks for these components in the `~/htmldev/components` folder. Inside this directory, you're free to
structure them how you want.

The components is rendered in the Twig control structure `{% strict false %}` block from the [zicht/framework-extra-bundle](https://github.com/zicht/framework-extra-bundle)
which means there's not need for strict `null` or empty checks on variables: a simple `{% if variable %}` will suffice.

**Recommended**: for easy conditional CSS classes, the HtmldevBundle automaticall loads the [zicht/twig-classes](https://github.com/zicht/twig-classes)
helper function:

```twig
{% set cx = {
    article: classes({
        'u-margin--b2': image.url,
        'u-margin--b0  u-text--center': not image.url,
    })
} %} 
   
<article class="{{ cx.article }}">
    ...
</article>
```

#### Pages

The bundle renders pages from the `~/htmldev/pages` directory. Add as many directories and files as need.

Example structure:

```
~/htmldev/pages/
  |- components/
  |---- buttons.html.twig # Can be viewed in browser using the URL /htmldev/components/buttons
  |---- cards.html.twig
  |---- headers.html.twig
  |- design-elements/
  |---- colors.html.twig
  |---- icons.html.twig
```

#### Rendering components in the styleguide

The HtmldevBundle contains a `load_data` Twig function to load the contents of a Yaml file in an array. The files
 are loaded from the `~/htmldev/data` directory. 
 
```twig
{% for component in load_data('buttons') %}
    {{ ui.styleguide_component(component.styleguide_type, component) }}
{% endfor %}
```

This example loads `~/htmldev/data/buttons.yml`:   

```yaml
-
    styleguide_title: Blue button
    styleguide_type: buttons/text
    text: Button
    color: blue

-
    styleguide_title: White button outline
    styleguide_type: buttons/text
    styleguide_dark: true
    outline: true
    text: Button
    color: white
```

This way, you can add components to the styleguide without typing Twig code. The keys that do not start with `styleguide_`
are only used to influence rendering of the component in the styleguide. The other keys of properties of the component itself.

The available options for rendering in the styleguide are:

` `styleguide_type`   
  The name of the component to show. For example, `buttons/text` correspond to the component `~/htmldev/components/buttons/text.html.twig`.
- `styleguide_title`   
  The title that will be rendered with the component.
- `styleguide_description`   
  An extra description of the component that will be rendered below the title
- `styleguide_dark` (true|false)
  A boolean indicating whether the styleguide should render the component on a dark background, for example for white buttons.
- `styleguide_component_width`
  Override the default width of the component in the styleguide. Use a pixel/percentage/viewport unit to change the width of the component
  next to the code example. Or to render the code example below the component, use `styleguide_component_width: full`.
  
#### Adding navigation

The HtmldevBundle supports two levels of navigation in the styleguide. 

Example of adding a submenu:

```yaml
-
    title: Components
    uri: /htmldev/components/buttons
    items:
        -
            title: Buttons
            uri: /htmldev/components/buttons
        -
            title: Cards
            uri: /htmldev/components/cards
```

#### Rendering icons

The Twig function `icon_list()` returns an list of file names inside the directory `~/htmldev/images/icons`. There's
also a Twig macro `icons()` that renders a list of icon file names inside a grid. So to render a grid of available icons:

```twig
{% extends '@htmldev/_base.html.twig' %}
{% import 'ZichtHtmldevBundle:macros:styleguide.html.twig' as styleguide %}


{% block head_title 'Icons - Styleguide' %}


{% block component_intro %}
    <h2 class="c-copy--h2">Icons</h2>
{% endblock %}


{% block component_list %}
    {{ styleguide.icons(icon_list()) }}
{% endblock %}
```

#### Rendering colors

The Twig function `color_palette` reads the color Sass map inside the given file name located in `~/htmldev/sass/variables` and returns the colors inside it as
an array. There's also a Twig macro that can render these colors inside a grid. So to render a grid of available colors:

```twig
{% extends '@htmldev/_base.html.twig' %}
{% import 'ZichtHtmldevBundle:macros:styleguide.html.twig' as styleguide %}


{% block head_title 'Colors - Styleguide' %}


{% block component_intro %}
    <h2 class="c-copy--h2">Colors</h2>
{% endblock %}


{% block component_list %}
    {{ styleguide.colors(color_palette('_zss-overrides.scss')) }}
{% endblock %}
```

The default color service assumes variables of the [ZSS](https://github.com/zicht/zss) framework, but feel free to
use a different service. See the [Customizing](#customizing) section.

### Using the styleguide in the project

#### Rendering components

The HtmldevBundle provides a `components` macro to render components from the styleguide anywhere in your application.

This wil load `~/htmldev/components/cards/cover.html.twig` with the given properties:
 
```twig
{% import 'ZichtHtmldevBundle:macros:components.html.twig' as ui %}

{{ ui.component('cards/cover, { 
    title: 'Hodor',
    url: '/some/page 
}) }}
```

#### Rendering SVG files

The HtmldevBundle provides an `svg` macro to render SVG's located in `~/htmldev` inline in the HTML source. This allows 
easy coloring of the SVG with `currentColor` and CSS.

This will render the contents of `~/htmldev/images/icons/arrow--right.svg` in the HTML:

```twig
{% import 'ZichtHtmldevBundle:macros:components.html.twig' as ui %}

{{ ui.svg('arrow--right, { 
    width: 20,
    height: 20,
    directory: 'images/icons'
}) }}
``` 

The second argument is an options object. These keys are available:

- `width` 
  The width that should be set on the `<svg />` element. This will override an existing `width` attribute.  
  The macro assumes `px`, so `width: 20` will be rendered as `<svg width="20px" />`. 
  Allowed values: [MDN](https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/width#svg).
- `height`    
  The height that should be set on the `<svg />` element. This will override an existing `height` attribute.  
  The macro assumes `px`, so `height: 20` will be rendered as `<svg height="20px" />`. 
  Allowed values: [MDN](https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/height#svg).
- `viewbox_x` and `viewbox_y`    
  The `x` and `y` values of the `viewbox` property. This will override an existing `viewbox` attribute. These arguments
  must both be passed, otherwise they will not be applied. 
  `viewbox_x: 20, viewbox_y: 30` will be rendered as `<svg viewbox="0 0 20 30" />`.
- `css_classes`   
  An array of CSS classes that will be applied to the `<svg />` element.
  `css_classes: ['u-white', 'u-block']` will be rendered as `<svg class="u-white  u-black" />`.
- `attributes`
  An array of extra attributes that will be applied to the `<svg />` element.
  This can be any attribute that's valid for the `<svg />` element.
  The default value for this parameter is `{ 'aria-hidden: 'true', 'role: 'img' }`.
- `title`
  This will add a `<title />` element inside the `<svg />` for accessibility improvements.
  Reference: [MDN](https://developer.mozilla.org/en-US/docs/Web/SVG/Element/title)
- `directory`
  The directory where the SVG file is located. This must be a directory inside the directory that's marked as 
  the root of the HtmldevBundle (default `~/htmldev`).
   
### Customising

There are several Symfony parameters available to override, to add a different implementation.

- `htmldev.directory` (default: `%kernel.root_dir%/../htmldev`)
  Change the styleguide directory. 
- `htmldev.controller`   
  The controller that handles the requests for pages inside the styleguide.
- `htmldev.color_service`
  The service that reads colors from a Sass variable in ZSS. To change the way this works, implement the `ColorServiceInterface` 
  class and change this parameter to your own class.
- `htmldev.svg_service`   
  The service that returns the contents of SVG files. To change the way this works, implement the `SvgServiceInterface` and
  change this parameter to your own class.

## Maintainer

* Robert van Kempen ([@DoNomal](https://github.com/DoNormal))
