/** @type {import("prettier").Config} */
const config = {
  printWidth: 100,
  tabWidth: 2,
  semi: false,
  singleQuote: true,
  tailwindConfig: './tailwind.config.js',
  plugins: [
    'prettier-plugin-tailwindcss',
  ],
}

export default config
