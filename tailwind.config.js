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
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                brand: {
                    primary: 'var(--brand-primary, #2563eb)',
                    secondary: 'var(--brand-secondary, #7c3aed)',
                    accent: 'var(--brand-accent, #06b6d4)',
                    dark: 'var(--brand-dark, #0f172a)',
                },
                surface: {
                    light: '#f0f4f8',
                    card: '#ffffff',
                },
            },
        },
    },

    plugins: [forms],
};
