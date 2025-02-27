import { resolve } from 'path'
import { terser } from 'rollup-plugin-terser'
import inject from '@rollup/plugin-inject'

export default {
    base: './',
    root: resolve(__dirname, 'src'),
    resolve: {
        alias: {
            'jquery': resolve(__dirname, 'node_modules/jquery'),
            'bootstrap': resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
    build: {
        manifest: "manifest.json",
        outDir: resolve(__dirname, 'public/compile'),
        assetsDir: 'public',
        emptyOutDir: true,
        minify: true,
        cssCodeSplit: true,
        sourcemap: false,
        ssr: false,
        rollupOptions: {
            input: {
                commonScripts: resolve(__dirname, 'src/Assets/js/common.js'),
                commonStyles: resolve(__dirname, 'src/Assets/scss/common.scss'),
            },
            output: {
                entryFileNames: ({name}) => {
                    const nameRegexp = /^(.*)Scripts$/;
                    if (nameRegexp.test(name ?? '')) {
                        const match = nameRegexp.exec(name ?? '');
                        return 'js/' + match[1] + '.js';
                    }
                    return 'js/[name].js';
                },
                assetFileNames: ({name}) => {
                    if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')){
                        return 'images/[name].[ext]';
                    }
                    if (/\.css$/.test(name ?? '')) {
                        const match = /^(.*)Styles.css$/.exec(name ?? '');
                        return 'css/' + match[1] + '.css';
                    }
                    return '[name].[ext]';
                },
                chunkFileNames: 'libs/[name].js',
            },
            plugins: [
                inject({
                    modules: {
                        $: 'jquery',
                        jQuery: 'jquery'
                    }
                }),
                terser({
                    format: {
                        comments: false
                    }
                })
            ]
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true
            }
        }
    },
};
