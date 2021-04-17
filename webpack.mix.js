const path = require('path')
const mix = require('laravel-mix');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');
const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');

mix.js('resources/js/main.js', 'public/js')

mix.extend('vuetify', new class {
  webpackConfig(config) {
    config.plugins.push(new VuetifyLoaderPlugin())
  }
})
mix.vuetify()

mix.options({
  postCss: [
    require('autoprefixer'),
  ],
})

mix.webpackConfig({
  output: {
    chunkFilename: 'js/chunks/[name].js',
  },
  plugins: [
    new CaseSensitivePathsPlugin()
  ],
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules(?!\/foundation-sites)|bower_components/,
        use: [
          {
            loader: 'babel-loader',
            options: Config.babel()
          }
        ]
      }
    ]
  },
  resolve: {
    alias: {
      '@': path.resolve(
        __dirname,
        'resources/js'
      )
    }
  }
})
