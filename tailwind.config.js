/** @type {import('tailwindcss').Config} */
import daisyui from "daisyui"

export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      screens: {
        'se': { 'max': '385px' },
        // => @media (min-width: 992px) { ... }
      },
      colors: {
        primary: "#ffe3e1",
        secondary: "#fb923c",
        error: "#ff6b56",
        success: "#4dd842",
      },

      animation: {
        'spin-slow': 'spin 50s linear infinite',
        'blink': 'blinker 1.5s linear infinite;',
      },
      keyframes: {
        blinker: {
          '50%': { opacity: '0' }
        },
      }
    },
  },
  plugins: [daisyui],
}