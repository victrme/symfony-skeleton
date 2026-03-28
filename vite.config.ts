import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import fs from "node:fs";

export default defineConfig({
    server: {
        host: '0.0.0.0',
        cors: true,
        https: {
            key: fs.readFileSync('certs/vite.key.pem'),
            cert: fs.readFileSync('certs/vite.crt.pem'),
        },
        watch: {
            ignored: [
                '**/node_modules/**',
                '**/.pnpm-store/**'
            ]
        },
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
