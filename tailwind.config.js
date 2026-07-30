/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#0B457F',
                    hover: '#083764',
                    light: '#EAF2FB',
                },
                secondary: {
                    DEFAULT: '#FFD42B',
                    hover: '#F4C400',
                },
                success: '#16A34A',
                warning: '#F59E0B',
                error: '#DC2626',
                danger: '#DC2626',
                info: '#0284C7',
                background: '#F8FAFC',
                surface: '#FFFFFF',
                border: '#E5E7EB',
                'text-primary': '#1F2937',
                'text-secondary': '#6B7280',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
