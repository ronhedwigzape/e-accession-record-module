import { defineStore } from 'pinia'

export const useStore = defineStore('store', {

  state:() => {
    return {
      app: {
        backendDir: 'app',
        title: 'E-Accession Record Module',
      }
    }
  },

  getters: {
    // get app name
    appName(state) {
      return import.meta.env.BASE_URL.replaceAll('/', '');
    },

    // get app url
    appURL(state) {
      if (window.location.hostname === 'localhost' && window.location.port === '5005')
        return `http://localhost${import.meta.env.BASE_URL}${state.app.backendDir}`;
      else
        return `${window.location.protocol}//${window.location.hostname}${import.meta.env.BASE_URL}${state.app.backendDir}`;
    },

    // get assets url
    assetsUrl(state) {
      if (window.location.hostname === 'localhost' && window.location.port === '5005')
        return `assets`
      else
        return `public`
    }

  }

});
