var webpack = require('webpack');
var path = require('path');

var jsPage = path.join(__dirname, 'js/page');
var jsLib = path.join(__dirname, 'js/lib');
var jsPlug = path.join(__dirname, 'js/plug');
var jsEntry = path.join(__dirname, 'js/entry');

var nodeRoot = path.join(__dirname, 'node_modules');

var process = new webpack.DefinePlugin({
    'process.env': {
        NODE_ENV: JSON.stringify('production')
    }
});
var CommonsChunkPlugin = require('webpack/lib/optimize/CommonsChunkPlugin');

module.exports = {
    entry: {
        login: jsEntry + '/login.js',
        register: jsEntry + '/register.js',
        findPsw: jsEntry + '/findPsw.js',
        bindtel: jsEntry + '/bindTel.js'
    },
    output: {
        path: path.resolve('./js/dist'),
        filename: '[name].bundle.js'
    },
    //plugins: [process],
    plugins: [
        new CommonsChunkPlugin('common.js')
    ],
    resolve: {

        extensions: ['', '.js', '.json', '.coffee'],
        alias: {
        	login: jsPage + '/login.js',
			register: jsPage + '/register.js',
            findUsername: jsPage + '/find_username.js',
            findVerify: jsPage + '/find_verify.js',
            setPsw: jsPage + '/set_pw.js',
            bindtel: jsPage + '/bindtel.js',
        	defaultRules: jsPage + '/defaultRules.js',
        	layer: jsPlug + '/layer.m/layer.m.js',
        	pwShow: jsPlug + '/pwShow.js',
        	validate: jsPlug + '/jquery.validate.min.js',
            verifyCode: jsPlug + '/verifyCode.js'
        }
    },
    module: {
        loaders: [{
            test: /\.css$/,
            loader: 'style!css'
        }, {
            test: /\.less$/,
            loader: 'style-loader!css-loader!autoprefixer-loader!less-loader'
        }, {
            test: /\.scss$/,
            loader: 'style-loader!css-loader!autoprefixer-loader!sass-loader'
        }, {
            test: /\.vue$/,
            loader: 'vue'
        }]
    }
}
759183