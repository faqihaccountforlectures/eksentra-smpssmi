/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Background & Dark Surface Palette (Elegan, tidak pekat gulita)
                'dark-bg': '#070b14',       // Midnight navy space
                'dark-surface': '#0d1527',  // Elevated surface
                'dark-card': '#111c34',     // Card surface
                'dark-card-hover': '#162444',// Hover card surface
                'dark-border': '#1e293b',   // Border halus
                
                // Neon Glow Accent Colors
                'glow-cyan': '#00f2fe',     // Electric cyan
                'glow-teal': '#14b8a6',     // Bright teal
                'glow-emerald': '#10b981',  // Mint / emerald
                'glow-blue': '#38bdf8',     // Sky blue
                'glow-purple': '#a855f7',   // Neon violet
                'glow-amber': '#f59e0b',    // Golden amber

                // Dashboard compatible colors (tetap aman untuk dashboard admin/siswa/coach)
                'navy-dark': '#0f172a',
                'navy-base': '#1d4ed8',
                'navy-light': '#60a5fa',
                'primary': '#0d9488',
                'primary-dark': '#0f766e',
                'primary-light': '#99f6e4',
                'accent': '#f59e0b',
                'accent-dark': '#d97706',
                'white-pure': '#ffffff',
                'white-off': '#f8fafc',
                'gray-muted': '#94a3b8',
            },
            fontFamily: {
                'sans': ['Inter', 'sans-serif'],
                'heading': ['"Plus Jakarta Sans"', 'sans-serif'],
                'body': ['Inter', 'sans-serif'],
            },
            boxShadow: {
                'neon-cyan': '0 0 25px -4px rgba(6, 182, 212, 0.45)',
                'neon-teal': '0 0 25px -4px rgba(20, 184, 166, 0.45)',
                'neon-amber': '0 0 25px -4px rgba(245, 158, 11, 0.45)',
                'glow-card': '0 10px 30px -10px rgba(0, 242, 254, 0.1)',
            },
            animation: {
                'float': 'float 5s ease-in-out infinite',
                'float-slow': 'float 8s ease-in-out infinite',
                'float-delay': 'float 6s ease-in-out 2.5s infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'glow-pulse': 'glowPulse 3s ease-in-out infinite',
                'spin-slow': 'spin 25s linear infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-14px)' },
                },
                glowPulse: {
                    '0%, 100%': { opacity: '0.4', transform: 'scale(1)' },
                    '50%': { opacity: '0.8', transform: 'scale(1.08)' },
                },
            }
        },
    },
    plugins: [],
};
