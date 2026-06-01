/** @type {import('tailwindcss').Config} */
export default {
  content: [    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1e40af',
        secondary: '#64748b',
        success: '#22c55e',
        warning: '#f59e0b',
        danger: '#ef4444',
        info: '#3b82f6',
      },
    },
  },
  plugins: [],
}

