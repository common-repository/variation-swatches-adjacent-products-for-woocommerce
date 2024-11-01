const path = require("path");
const TerserPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("css-minimizer-webpack-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");

module.exports = {
  entry: {
    "./templates/frontend/assets/js/wvsap.app"  : "./src/frontend/js/wvsap.app.js",
    "./templates/frontend/assets/css/wvsap.app" : "./src/frontend/scss/wvsap.app.scss",
    "./templates/admin/assets/js/appGlobal"     : "./src/admin/js/app.global.js",
    "./templates/admin/assets/css/style"         : "./src/admin/scss/app.scss"
  },
  output: {
    filename: "[name].min.js",
    path: path.resolve(__dirname),
  },
  devtool: "source-map",
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
      {
        test: /\.(sass|scss)$/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          "postcss-loader",
          "sass-loader",
        ],
      },
    ],
  },
  plugins: [
    new FixStyleOnlyEntriesPlugin(),
    new MiniCssExtractPlugin({
      filename: "[name].min.css",
    }),
  ],
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        minify: TerserPlugin.swcMinify,
        terserOptions: {
          format: {
            comments: false,
          },
        },
        extractComments: false,
      }),
      new OptimizeCSSAssetsPlugin({}),
    ],
  },
};
