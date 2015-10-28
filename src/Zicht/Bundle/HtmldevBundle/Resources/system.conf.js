/*global System*/

System.config({
    baseURL: '/bundles/<bundle>/javascript',

    transpiler: 'babel',

    map: {
        jquery: '../vendor/jquery/dist/jquery.js',
        hamburger: '../vendor/zicht-jquery-hamburger/src/js/jquery.hamburger.js'
    }
});