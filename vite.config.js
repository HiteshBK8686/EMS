import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    includeAbsolute: true,
                },
            },
        }),
       // laravel(["resources/css/app.css", "resources/ts/app.ts"]),
        laravel({
            input: ['resources/css/app.css', 'resources/ts/app.ts'],
            refresh: true,
        }),
    ],
    
    resolve: {
        alias: {
            "@/": path.join(__dirname, "/resources/ts/src/"),
            "~": path.join(__dirname, "/node_modules/"),
        },
    },
    build: {
        chunkSizeWarningLimit: 1600,
    },
});
