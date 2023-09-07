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
      },
      colors: {
        green : '#0C5446',
        orange: '#FF6700',
        lightbrown: '#DBD0BD',
        beige: '#FCFAEF',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('flowbite/plugin'), // add the flowbite plugin
    require('flowbite-typography'),
  ],
}

