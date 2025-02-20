const { addIconSelectors } = require("@iconify/tailwind");

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: "class",
    theme: {
        extend: {
           
            keyframes: {
                fadeIn: {
                    "0%": { opacity: 0, transform: "translateY(10px)" },
                    "100%": { opacity: 1, transform: "translateY(0)" },
                },
                slideInFromTop: {
                    "0%": {
                        transform: "translateY(-10%)",
                        opacity: 0,
                    },
                    "100%": {
                        transform: "translateY(0)",
                        opacity: 1,
                    },
                },
            },
            animation: {
                fadeIn: "fadeIn 1s ease-in-out",
                slideTop: "slideInFromTop 0.8s ease-out forwards",
            },
        },
    },
    plugins: [
        addIconSelectors(["mdi", "mdi-light"]),
        require("tailwind-scrollbar"),
    ],
};

// /** @type {import('tailwindcss').Config} */
// module.exports = {
//    content: ['./src/*.html'],
//    plugins: [
//        // Iconify plugin, requires writing list of icon sets to load
//        addIconSelectors(['mdi', 'mdi-light']),
//    ],
// };
