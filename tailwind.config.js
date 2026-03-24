/** @type {import('tailwindcss').Config} */
export default {
  content: [ 
    "./resources/**/*.blade.php",
    "./resources/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        "black": "#060606",
        "green-100": "#93a1a1",
        "green-mint": "#2aa198",
        "green-700": "#859900",
        "green-mint-light" : "#78c2ad",
        "gray-700": "#495057",
        "green-800": "#073642",
        "green-900": "#002b36",
        "primary": "#b58900",
        "warning": "#cb4b16",
        "info": "#268bd2"
      },
      fontFamily: {
        "hanken-grotesk": ["Hanken Grotesk", "sans-serif"]
      },
      fontSize: {
        "2xs": "0.625rem"
      },
    },
  },
  plugins: [],
};