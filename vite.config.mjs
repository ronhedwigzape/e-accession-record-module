// Plugins
import Components from 'unplugin-vue-components/vite'
import Vue from '@vitejs/plugin-vue'
import Vuetify, { transformAssetUrls } from 'vite-plugin-vuetify'
import ViteFonts from 'unplugin-fonts/vite'
import { defineConfig, loadEnv } from 'vite'
import { fileURLToPath, URL } from 'node:url'

// https://vitejs.dev/config/
export default defineConfig(async ({ mode }) => {
  process.env = {...process.env, ...loadEnv(mode, process.cwd())};
  const SKIP_BASE_PATH = process.env.VITE_SKIP_BASE_PATH;

  return {
    plugins: [
      Vue({
        template: { transformAssetUrls }
      }),
      Vuetify(),
      Components(),
      ViteFonts({
        google: {
          families: [{
            name: 'Roboto',
            styles: 'wght@100;300;400;500;700;900',
          }],
        },
      }),
    ],
    define: { 'process.env': {} },
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url))
      },
      extensions: [
        '.js',
        '.json',
        '.jsx',
        '.mjs',
        '.ts',
        '.tsx',
        '.vue',
      ],
    },
    base: SKIP_BASE_PATH === undefined || SKIP_BASE_PATH === 'false' ? '/e-accession-record-module/' : undefined,
    publicDir: 'assets',
    server: {
      host: 'localhost',
      port: 5005,
      strictPort: true
    },
    build: {
      outDir: 'public'
    }
  }
});
