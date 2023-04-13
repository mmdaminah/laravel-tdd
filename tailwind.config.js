/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            boxShadow: {
                'default': '0 0px 5px 0 rgba(0,0,0,0.08) !important'

            },
            colors: {
                'gray-light': "#F5F6F9",
                'blue': '#47cdff',
                'blue-light': '#8ae2fe',
                'default': 'var(--text-default-color)'
            },
            backgroundColor: {
                'page': 'var(--page-background-color)',
                'card': 'var(--card-background-color)',
                'button': 'var(--button-background-color)',
                'header': 'var(--header-background-color)'
            }
        },
    },
    plugins: [],
}
