const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/scripts/components/**/*.vue',
    './resources/scripts/pages/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        dark: {
          DEFAULT: '#0a1128',
          bg: '#0a1128',
          card: '#121836',
        },
        accent: {
          blue: '#00d1ff',
          hover: '#00bfff',
        },
        border: '#2a345c',
      },
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      boxShadow: {
        DEFAULT: '0 10px 20px rgba(0, 0, 0, 0.4)',
        lg: '0 15px 30px rgba(0, 0, 0, 0.3)',
      },
      borderRadius: {
        lg: '1rem',
        xl: '1rem',
      },
      animation: {
        fade: 'fadeIn 0.5s ease-in-out',
        slide: 'slideIn 0.6s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: 0 },
          '100%': { opacity: 1 },
        },
        slideIn: {
          '0%': { transform: 'translateY(20px)', opacity: 0 },
          '100%': { transform: 'translateY(0)', opacity: 1 },
        },
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
};
