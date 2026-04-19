/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // ===== TEMA CLARO =====
                'primary': '#1A3C5E',      // Azul profundo
                'secondary': '#C4A35A',    // Dorado quemado
                'background': '#F8F6F0',   // Blanco hueso
                'surface': '#EDEBE5',      // Gris muy claro
                'text-primary': '#2C2C2C', // Gris oscuro
                'text-secondary': '#6B6B6B', // Gris medio
                
                // ===== TEMA OSCURO (se usarán con dark:) =====
                'dark-primary': '#2A6B9E',     // Azul eléctrico
                'dark-secondary': '#D4B06A',   // Dorado suave
                'dark-background': '#0A1620',  // Negro azulado
                'dark-surface': '#1A2A38',     // Azul grisáceo oscuro
                'dark-text-primary': '#E5E5E5', // Gris claro
                'dark-text-secondary': '#8A9BB0', // Gris azulado
            },
            fontFamily: {
                'sans': ['Figtree', 'system-ui', 'sans-serif'],
                'display': ['Poppins', 'Figtree', 'sans-serif'],
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 4s ease-in-out infinite',
                'glow': 'glow 2s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                glow: {
                    '0%, 100%': { boxShadow: '0 0 5px rgba(196, 163, 90, 0.3)' },
                    '50%': { boxShadow: '0 0 20px rgba(196, 163, 90, 0.6)' },
                },
            }
        },
    },
    plugins: [],
}