/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        // Digitazio Brand Colors
        'atlantis': '#8CC63F',      // Primary (50% use)
        'yellow': '#FFFF00',        // Supporting (20% use)
        'white': '#FFFFFF',         // Supporting (20% use)
        'eerie-black': '#1C1C1C',   // Neutral
        // Additional shades for better UI
        'atlantis-light': '#A5D65A',
        'atlantis-dark': '#6B9F2F',
        'gray-50': '#F9FAFB',
        'gray-100': '#F3F4F6',
        'gray-200': '#E5E7EB',
        'gray-300': '#D1D5DB',
        'gray-400': '#9CA3AF',
        'gray-500': '#6B7280',
        'gray-600': '#4B5563',
        'gray-700': '#374151',
        'gray-800': '#1F2937',
        'gray-900': '#111827',
      },
      fontFamily: {
        'poppins': ['Poppins', 'sans-serif'],
        'raleway': ['Raleway', 'sans-serif'],
      },
      fontSize: {
        // Digitazio Typography Hierarchy
        'heading': ['44px', { lineHeight: '1.2', fontWeight: '600' }],
        'heading-sm': ['40px', { lineHeight: '1.2', fontWeight: '600' }],
        'subheading': ['18px', { lineHeight: '1.4', fontWeight: '400' }],
        'subheading-sm': ['16px', { lineHeight: '1.4', fontWeight: '400' }],
        'body': ['12px', { lineHeight: '1.5', fontWeight: '400' }],
        'body-sm': ['10px', { lineHeight: '1.5', fontWeight: '400' }],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
      boxShadow: {
        'digitazio': '0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19)',
      }
    },
  },
  plugins: [],
}
