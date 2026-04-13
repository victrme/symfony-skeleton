import js from '@eslint/js'
import globals from 'globals'
import tseslint from 'typescript-eslint'
import pluginVue from 'eslint-plugin-vue'
import json from '@eslint/json'
import css from '@eslint/css'
import { defineConfig } from 'eslint/config'

export default defineConfig([
    {
        ignores: ['node_modules/**', 'public/**', 'vendor/**', 'var/**', '.claude/**'],
    },
    {
        files: ['assets/**/*.{js,mjs,cjs,ts,mts,cts,vue}'],
        plugins: { js },
        extends: ['js/recommended'],
        languageOptions: { globals: globals.browser },
    },
    tseslint.configs.recommended,
    {
        files: ['assets/**/*.vue'],
        extends: [pluginVue.configs['flat/essential']],
        languageOptions: { parserOptions: { parser: tseslint.parser } },
    },
    {
        files: ['assets/**/*.json'],
        plugins: { json },
        language: 'json/json',
        extends: ['json/recommended'],
    },
    {
        files: ['assets/**/*.css'],
        ignores: ['assets/styles/daisyui.css'],
        plugins: { css },
        language: 'css/css',
        extends: ['css/recommended'],
    },
])
