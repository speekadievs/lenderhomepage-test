const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                jQuery: 'jquery',
                $: 'jquery',
                'window.jQuery': 'jquery',
            }),
        ],

        module: {
            rules: [
                {
                    test: /\.(js)$/,
                    exclude: /(node_modules)/,
                    loader: "babel-loader",
                    query: {
                        presets: [["@babel/preset-env", { modules: false }]]
                    }
                },
            ]
        },
    };
})

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
