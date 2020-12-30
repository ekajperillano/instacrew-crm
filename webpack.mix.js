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
 
mix.extend('wbConfig', new class {
	webpackConfig(webpackConfig) {
		webpackConfig.resolve.extensions.push('.js', '.vue', '.json'); // you don't need this on v4
		webpackConfig.resolve.alias = {
			'vue$': 'vue/dist/vue.esm.js',
			'@': __dirname + '/resources/js/',
			'configs': __dirname + '/resources/js/configs/',
			'stores': __dirname + '/resources/js/stores/',
			'utils': __dirname + '/resources/js/utils/',
			'mixins': __dirname + '/resources/js/mixins/',
			'modals': __dirname + '/resources/js/modals/',
			'components': __dirname + '/resources/js/components/',
			'pages': __dirname + '/resources/js/pages/',
		};
	}
});


mix.js('resources/js/app.js', 'public/js')
	.sass('resources/sass/app.scss', 'public/css').wbConfig().version();
