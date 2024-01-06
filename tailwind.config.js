/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["*/*.{html,js,php}", "node_modules/preline/dist/*.js"],
  plugins: [require("preline/plugin.js")],

  theme: {
    extend: {
      fontFamily: {
        title: ["Cardo", "serif"],
        subtitle: ["Varda", "sans-serif"],
      },
      colors: {
        green: {
          main: "#769368",
          nav: "#3F532C",
        },
        black: {
          main: "#252525",
          sub: "#706458",
        },
        brown: {
          main: "#A06056",
          secondary: "#3F532C",
          link: "#706458",
        },
        bgMain: {
          main: "#F3EEE8",
        },
      },
      backgroundImage: {
        "hero-bg": "url('../public/assets/hero-bg.jpg')",
      },
      screens: {
        xs: "400px",
        "3xl": "1680px",
        "4xl": "2200px",
      },
      maxWidth: {
        "10xl": "1512px",
      },
      borderRadius: {
        "5xl": "40px",
      },
    },
  },
};
