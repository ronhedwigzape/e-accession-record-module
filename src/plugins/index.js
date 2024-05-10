/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import vuetify from './vuetify'
import { createPinia } from 'pinia'
import router from "@/router/router";

export function registerPlugins (app) {
  app.use(vuetify)
  app.use(router)
  app.use(createPinia())
}
