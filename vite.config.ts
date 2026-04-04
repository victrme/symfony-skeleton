/// <reference types="vitest/config" />

import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'
import symfonyPlugin from 'vite-plugin-symfony'

export default defineConfig({
    plugins: [
        tailwindcss(),
        symfonyPlugin({
            viteDevServerHostname: 'localhost'
        })
    ],
    build: {
        rolldownOptions: {
            input: {
                app: './assets/index.ts'
            }
        }
    },
    server: {
        host: '0.0.0.0'
    },
    test: {
        environment: 'happy-dom',
        include: ['tests/**/*.test.ts']
    }
})
