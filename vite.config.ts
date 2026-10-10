/// <reference types="vitest/config" />

import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import Symfony from '@symfony/reprise/vite'
import vue from '@vitejs/plugin-vue'

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
