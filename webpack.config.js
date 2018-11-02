const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const path = require('path');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const webpack = require('webpack');

module.exports = function (env, argv) {
    return {
        entry: {
            styleguide: [
                './src/Zicht/Bundle/HtmldevBundle/Resources/sass/styleguide.scss'
            ]
        },

        output: {
            filename: '[name].min.js',
            path: path.resolve('./src/Zicht/Bundle/HtmldevBundle/Resources/public/css')
        },

        module: {
            rules: [
                {
                    // Load .scss files
                    test: /\.scss$/,
                    use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader']
                }
            ]
        },

        plugins: [
            new MiniCssExtractPlugin({
                filename: "[name].css",
                chunkFilename: "[id].css"
            }),
            new OptimizeCssAssetsPlugin({
                // colormin has a bug that transforms rgba into incorrect hsl values
                cssProcessorOptions: { colormin: false }
            }),
            new StyleLintPlugin({
                files: './src/Zicht/Bundle/HtmldevBundle/Resources/sass/**/*.scss'
            })
        ]
    };
};
