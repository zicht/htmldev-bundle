/*global System*/

System.config({
    baseURL: '/bundles/@bundle@/javascript',

    transpiler: 'babel'
});

/*
For es5 libraries use the "map" property like

map: {
    jquery: 'vendor/jquery/dist/jquery.js',
    underscore: 'vendor/underscore/underscore.js',
    backbone: 'vendor/backbone/backbone.js',
    react: 'vendor/react/react.js'
},


To define dependencies for es5 libraries use the "meta" property like

meta: {
    backbone: {
        deps: ['jquery', 'underscore']
    }
}
*/