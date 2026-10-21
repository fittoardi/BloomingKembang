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
            colors: {
                obsidian: '#1c1717',
                paper: '#ffffff',
                bone: '#f0f0f0',
                eucalyptus: '#a9aea9',
                sage: {
                    50: '#f5f7f5',
                    100: '#e6eae6',
                    200: '#cdd7cd',
                    300: '#aabda9',
                    400: '#849d84',
                    500: '#648064',
                    600: '#4e674e',
                    700: '#3e523e',
                    800: '#344334',
                    900: '#2c372c',
                },
                blush: {
                    50: '#fdf6f7',
                    100: '#f9edf0',
                    200: '#f4dcde',
                    300: '#ecc0c5',
                    400: '#df9caa',
                    500: '#cf7588',
                    600: '#b8546b',
                    700: '#9b4255',
                    800: '#813847',
                    900: '#5c2732',
                },
                botanic: '#4e674e', // Deep Elegant Sage Green
                bloom: '#b8546b',   // Dusty Rose Blossom Pink
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                none: '0px',
                DEFAULT: '0px',
                sm: '2px',
                md: '4px',
                lg: '6px',
                xl: '8px',
                '2xl': '12px',
                '3xl': '16px',
                full: '9999px',
            },
        },
    },

    plugins: [forms],
};
