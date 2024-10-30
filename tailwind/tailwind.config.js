/** @type {import('tailwindcss').Config} */
module.exports = {
  important: true,
  content: [
    "./views/**/*.php",
    "./widgets/**/views/**/*.php",
    "./widgets/**/tailwind/*.php",
    "./enums/**/tailwind/*.php",
    "./email/**/*.php",
    "./input.css",
    "./tailwind/input.css", // for deploy
  ],
  theme: {},
  plugins: [require("@tailwindcss/forms")],
};
