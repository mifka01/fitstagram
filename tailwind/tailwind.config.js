/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.php",
    "./widgets/**/views/*.php",
    "./widgets/**/tailwind/*.php",
    "./enums/**/tailwind/*.php",
    "./input.css",
  ],
  theme: {},
  plugins: [require("@tailwindcss/forms")],
};
