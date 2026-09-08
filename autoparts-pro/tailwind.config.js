/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#DC2626', // Racing Red
          foreground: '#FFFFFF',
        },
        dark: {
          DEFAULT: '#0A0A0A', // Obsidian Black
          100: '#141414',
          200: '#1E1E1E',
        },
        mid: {
          charcoal: '#2D2D2D',
          steel: '#6B7280',
        },
        light: {
          DEFAULT: '#FFFFFF',
          offwhite: '#F5F5F5',
        },
        accent: {
          DEFAULT: '#EA580C', // Burnt Orange
          foreground: '#FFFFFF',
        },
      },
      fontFamily: {
        heading: ['Rajdhani', 'Oswald', 'sans-serif'],
        body: ['Inter', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      animation: {
        'spin-slow': 'spin 3s linear infinite',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
      },
      keyframes: {
        'mechanical-slide': {
          '0%': { transform: 'translateX(-100%)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [],
}

