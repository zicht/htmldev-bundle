# Htmldev bundle

This bundle creates a htmldev environment where all frontend code is developed for a project. All templates are 
available through the path htmldev/filename (the filename must be without the .twig extension).

## Install

To start working with this bundle execute `bin/htmldev.sh`. This script creates a htmldev dir in the root of the 
project. It also creates images/style/sass/javascript dirs in the htmldev dir.
In the htmldev dir is a \_index.html.twig placed, this page displays all templates (files with the extension .html.twig 
and do not start with an underscore) that are placed in the htmldev dir.
A bower.json is placed in the root of the project along with a .bowerrc file, in the .bowerrc file is defined that bower 
installs all packages in htmldev/vendor.
For this bundle is a configuration yaml copied to the bundles config dir.

The only part that has to be done manually is adding the following to the to routing.yml
```
htmldev:
    resource: "@ZichtHtmldevBundle/Resources/config/routing.yml"
```
! Make sure the following line is added *above* any routing which has the /{locale} (or simular) pattern - like the PageBundle or (often) the site-specific controllers extending the PageController!

## Usage

If you want to include or import twig files in a template then use the syntax `@htmldev/<path>`. The path is relative to
the htmldev dir.

## Macros

### SVG

The SVG macros of the ZichtHtmldevBundle enable you to embed SVG documents from disk into your HTML. This allows you to scale and color your SVG with CSS. The two macros are `svg` for embedding regular SVG's for things like logos, and `svg_icon` for embedding icons. The main difference is that `svg_icon` automatically adds a few CSS classes and accessibility attributes.

#### `svg_icon`

The SVG is automatically decorated width CSS classes `c-icon` and `c-icon--%s` with `%s` being the supplied symbol name.

Parameters:

 * *symbol*: the name of the icon to load, without `.svg` extension.
 * *options*: an object to change the XML of the SVG.
     * *width*: the value in pixels the width attribute should have (default: 16)
     * *height*: the value in pixels the height attribute should have (default: 16)
     * *viewbox_x*: the integer value of the x part of the viewbox attribute (default: 16)
     * *viewbox_y*: the integer value of the y part of the viewbox attribute (default: 16)
     * *extra_css_classes*: an array of CSS classes that need to be applied to the icon (default: [])
     * *include_accessibility*: add `aria-hidden="true" role="img"` attributes (default: true)
     * *directory*: directory to load the svg from, relative to the htmldev directory (default: 'images/icons')

Note: the values of the width, height, viewbox_x and viewbox_y options are only applied if they're not already defined in the SVG. If your SVG has a width attribute, `options.width` is ignored.

Examples:

```
{% import '@htmldev/macros/components.html.twig' as ui %}

{{ ui.svg_icon('cross') }}
{{ ui.svg_icon('play', { extra_css_classes: ['c-icon--button'] }) }}
{{ ui.svg_icon('heart', { width: 20, height: 20 }) }}
```

You can now style the SVG with CSS animations, hovers and colors. If you use the `currentColor` value, you can simply change the `color` property of the parent to let the icon change color.

```
.c-icon {
    display: inline-block;
    fill: currentColor;
}
```

#### `svg`

This macro assumes you're loading an SVG file that has pre-defined dimensions in the SVG, so the default dimension values are empty strings. This macro also does not add CSS classes and accessibility attributes by default. Finally, it looks in `~/htmldev/images` for the file.

Parameters:

 * *name*: the name of the file to load, without `.svg` extension.
 * *options*: an object to change the XML of the SVG.
     * *width*: the value in pixels the width attribute should have (default: '')
     * *height*: the value in pixels the height attribute should have (default: '')
     * *viewbox_x*: the integer value of the x part of the viewbox attribute (default: '')
     * *viewbox_y*: the integer value of the y part of the viewbox attribute (default: '')
     * *css_classes*: an array of CSS classes that need to be applied to the SVG (default: [])
     * *accessibility_attributes*: an array of extra attributes (default: [])
     * *directory*: directory to load the SVG from, relative to the htmldev directory (default: 'images')

Note: the values of the width, height, viewbox_x and viewbox_y options are only applied if they're not already defined in the SVG. If your SVG has a width attribute, `options.width` is ignored.

Examples:

```
{% import '@htmldev/macros/components.html.twig' as ui %}

{{ ui.svg('logo') }}
{{ ui.svg('logo', { css_classes: ['c-logo'] }) }}
{{ ui.svg('logo', { width: 20, height: 20 }) }}
```