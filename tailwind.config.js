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
                primary: {
                    DEFAULT: '#2D5A4C',
                    50: '#E8F0ED',
                    100: '#D1E1DB',
                    200: '#A3C3B7',
                    300: '#75A593',
                    400: '#47876F',
                    500: '#2D5A4C',
                    600: '#244A3E',
                    700: '#1B3A30',
                    800: '#122A22',
                    900: '#091A14',
                },
                secondary: {
                    DEFAULT: '#4CAF50',
                    50: '#EDF7ED',
                    100: '#DBEFDB',
                    200: '#B7DFB7',
                    300: '#93CF93',
                    400: '#6FBF6F',
                    500: '#4CAF50',
                    600: '#3D8C40',
                    700: '#2E6930',
                    800: '#1F4620',
                    900: '#102310',
                },
                accent: {
                    DEFAULT: '#FBC02D',
                    50: '#FFF8E1',
                    100: '#FFF0B3',
                    200: '#FFE082',
                    300: '#FFD54F',
                    400: '#FFCA28',
                    500: '#FBC02D',
                    600: '#F9A825',
                    700: '#F57F17',
                    800: '#E65100',
                    900: '#BF360C',
                },
                surface: {
                    DEFAULT: '#F8F9FA',
                    card: '#FFFFFF',
                    border: '#E0E0E0',
                },
                content: {
                    DEFAULT: '#1A1A1A',
                    body: '#666666',
                    muted: '#9E9E9E',
                },
            },
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'xl': '12px',
                '2xl': '16px',
            },
            boxShadow: {
                'card': '0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04)',
                'card-hover': '0 4px 12px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.04)',
                'elevated': '0 10px 25px rgba(0, 0, 0, 0.08), 0 4px 10px rgba(0, 0, 0, 0.04)',
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
        },
    },

    plugins: [forms],
};
