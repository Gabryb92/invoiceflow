import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
//import browserSync from "vite-plugin-browser-sync";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        // browserSync({
        //     proxy: "http://127.0.0.1:8000",
        //     files: [
        //         "app/**/*.php",
        //         "resources/views/**/*.blade.php",
        //         "public/**/*.css",
        //         "resources/js/**/*.js",
        //     ],
        //     open: false,
        //     port: 3000,
        //     ghostMode: {
        //         clicks: true,
        //         scroll: true,
        //         forms: true,
        //     },
        // }),
    ],
});
