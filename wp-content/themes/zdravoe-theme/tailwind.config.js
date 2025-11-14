/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./**/*.php",
    "./react-version/**/*.{ts,tsx}",
  ],
  theme: {
    container: {
      center: true,
      padding: "1rem",
    },
    extend: {},
  },
  plugins: [
    require('@tailwindcss/line-clamp'),
  ],
};

