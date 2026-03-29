import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";

export default defineConfig({
    server: {
        host: '0.0.0.0',
    },
    plugins: [
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
});
