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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'cream': '#F7EEDC',
                'cream-dark': '#E8DCC4',
                'brown': '#A67C52',
                'brown-light': '#C19A6B',
                'brown-dark': '#8B6840',
                'heart-red': '#C62828',
                'heart-red-light': '#E53935',
                'heart-red-dark': '#B71C1C',
            },
            boxShadow: {
                'glow': '0 0 20px rgba(198, 40, 40, 0.5)',
                'smooth': '0 4px 20px rgba(0, 0, 0, 0.08)',
            },
        },
    },

    plugins: [forms],
};
