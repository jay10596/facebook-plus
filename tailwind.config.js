module.exports = {
    purge: [
        './resources/views/**/*.blade.php',
        './resources/css/**/*.css',
    ],
    theme: {
        extend: {
            width: {
                '35': '8.7rem',
                '72': '18rem',
                '80': '20rem',
                '96': '24rem'
            },
            height: {
                '35': '8.7rem',
                '72': '20rem',
                '80': '24rem',
            },
            fontSize: {
                '11xl': '8.5rem',
            },
            margin: {
                '36': '9rem',
            }
        }
    },
    variants: {},
    plugins: [
        require('@tailwindcss/custom-forms')
    ]
}
