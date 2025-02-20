import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/jquery-3-7-1.js",
                "resources/fontawesome/css/all.min.css",
                "resources/css/jquery.dataTables.min.css",
                "resources/js/jquery.dataTables.min.js",
                "resources/js/chart.js"
            ],
            refresh: true,
        }),
    ],
});
