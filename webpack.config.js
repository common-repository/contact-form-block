const TerserPlugin = require('terser-webpack-plugin');
const path = require('path');

const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

function createConfig(env, options) {
  const isProduction = options.mode === 'production';
  const isAnalysis = env && env.analysis === 'true';

  const plugins = [];
  if (isAnalysis) {
    plugins.push(new BundleAnalyzerPlugin());
  }
  const regexNodeModules = /[\\/]node_modules[\\/]/;
  const regexNekoUI = /[\\/]neko-ui[\\/]/;

  return {
    context: __dirname,
    mode: isProduction ? 'production' : 'development',
    plugins: plugins,
    entry: {
      index: './app/js/index.js'
    },
    devtool: isProduction ? 'nosources-source-map' : 'eval-source-map',
    output: {
      filename: '[name].js',
      path: __dirname + '/app/',
      chunkLoadingGlobal: 'wpJsonMCFB'
    },
    optimization: {
      minimizer: [new TerserPlugin({
        extractComments: false,
      })],
      splitChunks: {
        chunks: 'all',
        name: 'vendor',
        cacheGroups: {
          vendor: {
            test: function (module) {
              if (module.resource) {
                return (module.context.match(regexNodeModules) || module.context.match(regexNekoUI));
              }
            },
            name: "vendor"
          }
        }
      }
    },
    externals: {
      "react": "React",
      "react-dom": "ReactDOM"
    },
	
    resolve: {
      alias: {
        '@app': path.resolve(__dirname, './app/js/'),
        '@common': path.resolve(__dirname, './common/js/'),
        '@neko-ui': path.resolve(__dirname, '../neko-ui/'),
        'styled-components': path.resolve('./node_modules/styled-components'),
      }
    },
    module: {
      rules: [{
        test: /\.js$/,
        include: [
          path.resolve(__dirname, './app/js/'),
          path.resolve(__dirname, './common/js/'),
          path.resolve(__dirname, '../neko-ui/'),
        ],
        exclude: [
          path.resolve(__dirname, 'node_modules')
        ],
        use: { 
          loader: 'babel-loader',
          options: {
            presets: ["@babel/preset-env", "@babel/preset-react"]
          }
        },
      }
      ]
    }
  };
}

module.exports = createConfig;