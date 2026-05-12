import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                },
                accent: {
                    50: '#faf5f0',
                    100: '#f0e6d8',
                    200: '#dcc4a8',
                    300: '#c4a574',
                    400: '#a67c52',
                    500: '#8b5a3c',
                    600: '#6d4530',
                    700: '#523528',
                    800: '#3d2820',
                    900: '#2a1b16',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                card: '0 4px 24px -4px rgb(0 0 0 / 0.08), 0 8px 16px -8px rgb(0 0 0 / 0.06)',
            },
        },
    },
    plugins: [forms],
};
