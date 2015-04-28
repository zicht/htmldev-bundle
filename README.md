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

## Usage

If you want to include or import twig files in a template then use the syntax `@htmldev/<path>`. The path is relative to
the htmldev dir.