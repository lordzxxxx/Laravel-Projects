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
                    // Legacy aliases (do not remove – referenced across many templates).
                    dark: '#34543F',
                    primary: '#457359',
                    medium: '#799F76',
                    soft: '#CBDFC6',
                    // New forest scale (token-aligned with CSS variables).
                    900: 'var(--brand-900)',
                    800: 'var(--brand-800)',
                    700: 'var(--brand-700)',
                    600: 'var(--brand-600)',
                    500: 'var(--brand-500)',
                    300: 'var(--brand-300)',
                    200: 'var(--brand-200)',
                    100: 'var(--brand-100)',
                    50:  'var(--brand-50)',
                },
                ink: {
                    900: 'var(--ink-900)',
                    800: 'var(--ink-800)',
                    700: 'var(--ink-700)',
                    600: 'var(--ink-600)',
                    500: 'var(--ink-500)',
                    400: 'var(--ink-400)',
                    300: 'var(--ink-300)',
                    200: 'var(--ink-200)',
                    100: 'var(--ink-100)',
                    50:  'var(--ink-50)',
                },
                accent: {
                    rust:  'var(--accent-rust)',
                    gold:  'var(--accent-gold)',
                    cream: 'var(--accent-cream)',
                },
                status: {
                    success: 'var(--status-success)',
                    warning: 'var(--status-warning)',
                    danger:  'var(--status-danger)',
                    info:    'var(--status-info)',
                },
            },
            boxShadow: {
                'token-sm': 'var(--shadow-sm)',
                'token-md': 'var(--shadow-md)',
                'token-lg': 'var(--shadow-lg)',
            },
            borderRadius: {
                'token-sm':  'var(--radius-sm)',
                'token-md':  'var(--radius-md)',
                'token-lg':  'var(--radius-lg)',
                'token-xl':  'var(--radius-xl)',
                'token-2xl': 'var(--radius-2xl)',
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
