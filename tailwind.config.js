module.exports = {
    purge: [
        './resources/views/**/*.blade.php',
        './resources/css/**/*.css',
    ],
    theme: {
        extend: {
            width: {
                '72': '18rem',
                '80': '20rem',
                '96': '24rem'
            },
            height: {
                '72': '20rem',
                '80': '24rem',
            },
            fontSize: {
                '11xl': '9rem',
            }
        }
    },
    variants: {},
    plugins: [
        require('@tailwindcss/custom-forms')
    ]
}
