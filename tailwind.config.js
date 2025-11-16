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
        //tema solar
        "green-100": "#93a1a1", // svijetlo zelena 
        "green-mint": "#2aa198",
        "green-700": "#859900",
        "green-mint-light" : "#78c2ad",
        "gray-700": "#495057",  // зелена
        "green-800": "#073642",
        "green-900": "#002b36",
          "primary": "#b58900", // oker
        "warning": "#cb4b16", // narandžasta
        "info": "#268bd2"  // svijetlo plava


      },
      fontFamily: {
        "hanken-grotesk": ["Hanken Grotesk", "sans-serif"]
      },
      fontSize: {
        "2xs": "0.625rem" // 10px 
      },
},
  plugins: [],
  navbar: {
   
    "navbar-width": "calc(100% - 2rem)"
  }
}
}
