import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Space Grotesk', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    maroon: '#992c31',
                    maroonDark: '#7b1f26',
                    maroonDeep: '#5c161c',
                    cream: '#f4e8b8',
                    sand: '#f1d7a4',
                    accent: '#f1841d',
                    charcoal: '#241b1b',
                    offwhite: '#fffbf2',
                },
                emerald: {
                    50: '#fff9f2',
                    100: '#fdf0df',
                    200: '#f7e0c2',
                    300: '#f1d0a4',
                    400: '#f1a85f',
                    500: '#f1841d',
                    600: '#c9671a',
                    700: '#a25018',
                    800: '#7c3a14',
                    900: '#5b2a10',
                },
                teal: {
                    50: '#fff7f7',
                    100: '#feecec',
                    200: '#fbd7d8',
                    300: '#f4b6b8',
                    400: '#e4878c',
                    500: '#c95a63',
                    600: '#992c31',
                    700: '#7b1f26',
                    800: '#5c161c',
                    900: '#3f0f14',
                },
            },
        },
    },

    plugins: [forms],
};
