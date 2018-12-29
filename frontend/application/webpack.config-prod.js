var path = require('path');
var webpack = require('webpack');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
    mode: 'production',
    entry: ['babel-polyfill', 'whatwg-fetch', './index.js'],
    output: {
        path: path.resolve(__dirname, './../../public/js/dist'),
        publicPath: './../../public/js/dist/',
        filename: 'build.js'
    },
    module: {
        rules: [
            {
                test: /\.(scss|css|less)$/,
                use: [{loader: 'vue-style-loader'}, {loader: 'less-loader'}],
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.sass$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    {
                        loader: 'sass-loader',
                        options: {
                            indentedSyntax: true
                        }
                    }
                ]
            },
            {
                test: /\.styl(us)?$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    'stylus-loader'
                ]
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader'
                }
            },
            {
                test: /\.(png|jpg|gif|svg)$/,
                loader: 'file-loader',
                options: {
                    name: '[name].[ext]?[hash]'
                }
            },
        ]
    },
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        }
    },
    devServer: {
        historyApiFallback: true,
        noInfo: true
    },
    performance: {
        hints: false
    },
    devtool: 'source-map',
    plugins: [
        new VueLoaderPlugin(),
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            }
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true
        })
    ]
};

const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports.optimization = {
        minimizer: [
            new UglifyJsPlugin({
                sourceMap: true,
                uglifyOptions: { ecma: 8 },
            })
        ]
};