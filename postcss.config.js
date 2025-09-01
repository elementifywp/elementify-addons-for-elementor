// postcss.config.js
module.exports = {
  plugins: [
    require("postcss-nested"),
    //require("@tailwindcss/postcss"),
    require("autoprefixer")({
      overrideBrowserslist: ['last 2 versions', '> 1%', 'ie >= 11']
    })
  ]
}