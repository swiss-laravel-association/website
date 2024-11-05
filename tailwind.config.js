import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['helvetica', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': {
                    '50': '#fff2f1',
                    '100': '#ffe1df',
                    '200': '#ffc8c5',
                    '300': '#ffa29d',
                    '400': '#ff6c64',
                    '500': '#ff2c20',
                    '600': '#ed2115',
                    '700': '#c8170d',
                    '800': '#a5170f',
                    '900': '#881a14',
                    '950': '#4b0804',
                },

            },
            animation: {
                scale: 'scale 2s ease-in-out infinite',
            },
            keyframes: {
                scale: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.05)' },
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
};
