/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./assets/js/**/*.js"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Noto Serif Lao', 'serif'],
        serif: ['Noto Serif Lao', 'serif'],
        'serif-lao': ['Noto Serif Lao', 'serif'],
        'sans-lao': ['Noto Serif Lao', 'serif'],
      },
      colors: {
        burgundy: {
          50: '#FFFDF2',
          100: '#FAF7EC',
          200: '#F4EFE0',
          600: '#7E253A',
          700: '#6B1D2F',
          800: '#531321',
          900: '#3D0B16',
          950: '#26050E',
        },
        gold: {
          100: '#FCF7E8',
          200: '#F8EECF',
          300: '#F3E1B1',
          400: '#EAD29B',
          500: '#DCAE6C',
          600: '#CD9947',
          700: '#B28135',
          800: '#946726',
          900: '#774F1A',
        },
        wood: {
          50: '#F9F5F0',
          100: '#F2E8DC',
          200: '#E3D0BE',
          300: '#C5A080',
          400: '#A3724C',
          500: '#83522E',
          600: '#673D1E',
          700: '#4E2B13',
          800: '#381D0B',
          900: '#261205',
        },
        coffee: {
          50: '#FAF6F0',
          100: '#F3E9DD',
          200: '#E2CEBA',
          300: '#C8A382',
          400: '#AA7952',
          500: '#8A562B',
          600: '#6E401C',
          700: '#542E12',
          800: '#3D1F0A',
          900: '#271204',
        },
        beer: {
          50: '#FFFDF0',
          100: '#FFF9D6',
          200: '#FFF0A8',
          300: '#FDE070',
          400: '#F5C83B',
          500: '#E2AA1E',
          600: '#BE8510',
          700: '#996309',
          800: '#734605',
          900: '#4E2C02',
        },
        cream: {
          50: '#FFFDF2',
          100: '#FAF7EC',
          200: '#F4EFE0',
          300: '#E7DFCC',
        }
      }
    },
  },
  plugins: [],
}
