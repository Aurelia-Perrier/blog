/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js" // set up the path to the flowbite package
  ],
  theme: {
    extend: {
      screens: {
        'sm': {'max': '425px'},
      }
    },
  },
  plugins: [
    require('flowbite/plugin'), // add the flowbite plugin
    require('flowbite-typography')
  ],
}

