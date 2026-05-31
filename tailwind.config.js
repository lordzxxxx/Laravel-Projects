import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Space Grotesk', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    dark: '#1B5E20',
                    primary: '#2E7D32',
                    medium: '#43A047',
                    soft: '#C8E6C9',
                },
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.6s ease forwards',
                'fade-in-up-d1': 'fadeInUp 0.6s ease 0.15s forwards',
                'fade-in-up-d2': 'fadeInUp 0.6s ease 0.3s forwards',
                'fade-in-up-d3': 'fadeInUp 0.6s ease 0.45s forwards',
            },
        },
    },

    plugins: [forms],
};
