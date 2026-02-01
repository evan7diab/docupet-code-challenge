import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                docupet: {
                    blue: '#2563eb',
                    'blue-dark': '#1d4ed8',
                    green: '#22c55e',
                },
            },
        },
    },
    plugins: [],
};
