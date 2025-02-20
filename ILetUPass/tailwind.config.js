/** @type {import('tailwindcss').Config} */
const plugin = require("tailwindcss/plugin");
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            keyframes: {
                "fade-in-down": {
                    "0%": {
                        opacity: "0",
                        transform: "translateY(-10px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },
                "fade-in-up": {
                    "0%": {
                        opacity: "0",
                        transform: "translateY(10px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateY(0)",
                    },
                },

                "no-fade-up": {
                    "0%": {
                        transform: "translateY(10px)",
                    },
                    "100%": {
                        transform: "translateY(0)",
                    },
                },

                "no-fade-down": {
                    "0%": {
                        transform: "translateY(-10px)",
                    },
                    "100%": {
                        transform: "translateY(0)",
                    },
                },

                "fade-in-left": {
                    "0%": {
                        opacity: "0",
                        transform: "translateX(10px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateX(0)",
                    },
                },
                "fade-in-right": {
                    "0%": {
                        opacity: "0",
                        transform: "translateX(-10px)",
                    },
                    "100%": {
                        opacity: "1",
                        transform: "translateX(0)",
                    },
                },

                "no-fade-right": {
                    "0%": {
                        transform: "translateX(-10px)",
                    },
                    "100%": {
                        transform: "translateX(0)",
                    },
                },

                "no-fade-left": {
                    "0%": {
                        transform: "translateX(10px)",
                    },
                    "100%": {
                        transform: "translateX(0)",
                    },
                },
            },
            animation: {
                "fade-in-down": "fade-in-down 0.5s ease-out",
                "fade-in-up": "fade-in-up 0.5s ease-out",
                "fade-in-up1": "fade-in-up 1.2s ease-out",
                "fade-in-left": "fade-in-left 0.5s ease-in",
                "fade-in-right": "fade-in-right 0.5s ease-in",
                "no-fade-up": "no-fade-up 0.3s ease-in",
                "no-fade-down": "no-fade-down 0.5s ease-out",
                "no-fade-right": "no-fade-right 0.3s ease-in",
                "no-fade-left": "no-fade-left 0.5s ease-out",
            },
        },

        textShadow: {
            sm: "0 2px 2px var(--tw-shadow-color)",
            DEFAULT: "0 2px 4px var(--tw-shadow-color)",
            lg: "0 8px 16px var(--tw-shadow-color)",
        },
    },
    plugins: [
        plugin(function ({ matchUtilities, theme }) {
            matchUtilities(
                {
                    "text-shadow": (value) => ({
                        textShadow: value,
                    }),
                },
                { values: theme("textShadow") }
            );
        }),
    ],
};
