import { defineConfig } from 'vite';
import copy from 'rollup-plugin-copy';
import laravel from 'laravel-vite-plugin';
import path, { resolve } from 'path';
import sass from 'sass';
import postcss from 'postcss-custom-properties';

const directory = path.basename(path.resolve(__dirname))
const root = resolve(__dirname, 'resources')
const source= `resources/assets/`
const dist= `public/assets/`

export default defineConfig({
    resolve: {
        alias: {
            '@': root,
        }
    },
    plugins: [
        laravel({
            input: [
                `${source}scss/app.scss`,
                `${source}js/app.js`
            ],
            refresh: true,
        }),
        copy({
            targets: [
                { src: `${source}img/**/*`, dest: `${dist}img` },
                { src: `${source}js/**/*`, dest: `${dist}js` },
            ],
            hook: 'writeBundle'
        }),
        postcss({
            config: {
                path: './postcss.config.js',
            },
            extract: true
        })
    ],
    css: {
        preprocessorOptions: {
            scss: {
                implementation: sass,
            },
        },
    },
    build: {
        sourcemap: true, // eval or 'inline', 'hidden', 'nosources'
        rollupOptions: {
            output: {
                // Configure specific output names here
                entryFileNames: `js/[name].js`,
                chunkFileNames: `js/[name].js`,
                assetFileNames: ({name}) => {
                    if (name.endsWith('.css')) return `css/[name].css`;
                    if (name.endsWith('.js')) return `js/[name].js`;
                    return `[name].[ext]`;
                }
            }
        },
        outDir: `${dist}`,
    },
});