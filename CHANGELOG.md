* 2.1.0
    * Added RenderCommand (`zicht:htmldev:render`) which renders a twig template and outputs the results to a file
    * Default file now is "index.html.twig" in stead of "_index.html.twig"
    * The 'indexAction' is removed and the defaultAction now serves all templates (except starting with underscore), defaulting to "index.html.twig"
    * The routing annotation have their prefix removed, and the prefix is now part of routing.yml. If you configured your own routing, please be aware when updating

* 2.2.0
    * Add twig functions for embedding SVG and SVG icons in HTML.