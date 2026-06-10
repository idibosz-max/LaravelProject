/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],
    theme: {
        extend: {
            colors: {
                'dib-red': '#DC2626',
                'dib-red-dark': '#991B1B',
                'dib-black': '#0A0A0A',
                'dib-dark': '#111111',
                'dib-gray': '#1A1A1A',
            },
            fontFamily: {
                'display': ['"Bebas Neue"', 'sans-serif'],
                'body': ['"DM Sans"', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
