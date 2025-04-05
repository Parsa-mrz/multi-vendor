/** @type {import('tailwindcss').Config} */
import { colors } from "tailwindcss/colors";
export default {
    content: [
        "./resources/views/**/*.blade.php",
        "./app/Http/Livewire/**/*.php",
    ],
    theme: {
        colors: {
            ...colors,
        },
        extend: {},
    },
    plugins: [],
};
