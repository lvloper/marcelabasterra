export default {
    content: [
        './app/Filament/**/*.php',
        './app/Livewire/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
  safelist: [
    'mb-0',
    'mb-6',
    'mb-12',
    'mb-24',
    'md:mb-0',
    'md:mb-6',
    'md:mb-12',
    'md:mb-24',
    'font-bellota',
    'font-heavy',
  ],

  theme: {
    fontSize: {
      base: 'var(--font-size-base)',
    },
    screens: {
      'xs': '480px',
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1080px',
      '2xl': '1280px',
      '3xl': '1340px',
      '4xl': '1640px',
      '5xl': '1920px',
    },
    extend: {
      container: {
        center: true,
        padding: {},
        screens: {
          'sm': '640px',
          'md': '768px',
          'lg': '1024px',
          'xl': '1280px',
          '2xl': '1280px',
          '3xl': '1530px',
          '4xl': '1530px',
        }
      },
      fontFamily: {
<<<<<<< HEAD
        sans: ['Work Sans', 'sans-serif'],
        pt: ['Work Sans', 'sans-serif'],
        poppins: ['Work Sans', 'sans-serif'],
        bellota: ['Bellota Text', 'cursive'],
        heavy: ['Bellota Text', 'cursive'],
=======
        sans: ['Bellota Text', 'sans-serif'],
        source: ['Source Serif 4', 'serif'],
>>>>>>> 85ac118f44c5d2c1cda44c274bef3d05c175fc3c
      },
      fontSize: {
        'xs': '0.75rem',
        'sm': '0.85rem',
        'base': '0.85rem',
        'md': '1.1rem',
        'lg': '1.3rem',
        'xl': '1.4rem',
        '2xl': '1.6rem',
        '3xl': '2rem',
        '4xl': '2.7rem',
        '5xl': '3.5rem',
      },
      dropShadow: {
        'custom': [
          '0 4px 5px rgb(0 0 0 / 0.1)',
          '0 2px 4px rgb(0 0 0 / 0.6)'
        ]
      },
      colors: {
        primary: {
          DEFAULT: 'var(--color-primary)',
          hover: 'var(--color-primary-hover)',
        },
        secondary: {
          DEFAULT: 'var(--color-secondary)',
          hover: 'var(--color-secondary-hover)',
        },
        secondarylight: {
          DEFAULT: 'var(--color-secondary-light)',
        },
        accent: {
          DEFAULT: 'var(--color-accent)',
          hover: 'var(--color-accent-hover)',
        },
        black: 'var(--color-black)',
        white: 'var(--color-white)',
        gray: {
          DEFAULT: 'var(--color-gray)',
          2: 'var(--color-gray-2)',
          3: 'var(--color-gray-3)',
        },
      },
    },
  },
  plugins: [],
}
