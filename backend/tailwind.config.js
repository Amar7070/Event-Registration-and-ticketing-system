import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                primary: {
                    DEFAULT: '#4E7D5B',
                    50: '#F7F9F2',
                    100: '#EFF3E5',
                    200: '#DDE7CB',
                    300: '#CADBA2',
                    400: '#B8CF78',
                    500: '#4E7D5B',
                    600: '#436D0D',
                    700: '#395D0B',
                    800: '#2F4E09',
                    900: '#263E07',
                },
                cream: {
                    DEFAULT: '#FDFBF7',
                    50: '#FEFEFD',
                    100: '#FDFDFC',
                    200: '#FBFAF8',
                    300: '#FDFBF7',
                    400: '#F5F2E9',
                    500: '#F1ECD5',
                },
                slate: {
                    ...defaultTheme.colors.slate,
                    900: '#1E293B',
                },
                accent: {
                    amber: '#D97706',
                    sage: '#4D7C0F',
                }
            },
            borderRadius: {
                '2xl': '16px',
                '3xl': '24px',
                '4xl': '32px',
            },
            boxShadow: {
                'premium': '0 10px 30px -10px rgba(0, 0, 0, 0.05)',
                'premium-hover': '0 20px 40px -15px rgba(0, 0, 0, 0.1)',
            }
        },
    },

    plugins: [forms, typography],
};
