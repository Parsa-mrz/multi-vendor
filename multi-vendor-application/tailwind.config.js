import { colors } from "tailwindcss/colors";

/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/views/**/*.blade.php"],
    theme: {
        colors: {
            ...colors,
        },
        extend: {},
    },
    plugins: [],
};
