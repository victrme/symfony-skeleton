/// <reference types="vitest/config" />

import tailwindcss from '@tailwindcss/vite'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import Symfony from '@symfony/reprise/vite'

export default defineConfig({
    plugins: [
        vue(),
        tailwindcss(),
        Symfony(),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        rolldownOptions: {
            input: {
                app: './assets/index.ts',
            },
        },
    },
    test: {
        environment: 'happy-dom',
        include: ['tests/**/*.test.ts'],
    },
})
