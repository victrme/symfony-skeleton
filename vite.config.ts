import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        symfonyPlugin({
            viteDevServerHostname: 'localhost'
        }),
    ],
    build: {
        rolldownOptions: {
            input: {
                'app': './assets/index.ts',
            },
        }
    },
    server: {
        host: '0.0.0.0',
    },
});
