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
                    maroon: '#b71917',
                    maroonDark: '#7b0503',
                    maroonDeep: '#520202',
                    cream: '#f4e8b8',
                    sand: '#f1d7a4',
                    accent: '#f5c347',
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
                    50: '#fff5f3',
                    100: '#fde3e1',
                    200: '#fac0bc',
                    300: '#f2948b',
                    400: '#e15843',
                    500: '#c3271d',
                    600: '#a11714',
                    700: '#7d0d0a',
                    800: '#590705',
                    900: '#3b0302',
                },
            },
        },
    },

    plugins: [forms],
};
