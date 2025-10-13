import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
      },
      colors: {
        primary: "#1e64e6", // blue accent
        secondary: "#000000", // black
        background: "#ffffff", // white
        info: "#0EA5E9", // yellow
        success: "#22C55E", // green
        warning: "#F59E0B", // amber
        danger: "#EF4444", // red
      },
    },
  },

  plugins: [forms],
};
