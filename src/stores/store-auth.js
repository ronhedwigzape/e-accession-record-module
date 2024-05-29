import { defineStore } from 'pinia';
import $ from 'jquery';
import { useStore } from '@/stores/store';
import router from "@/router/router";
import {reactive} from "vue";

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    dialog: false,
    isPasswordShown: false,
    loading: false,
    username: '',
    password: '',
    signingOut: false,
    signedOut: false,
    rules: reactive({
      required: value => !!value || 'Login credential is required.',
    }),
  }),

  getters: {
    getUser(state) {
      return state.user;
    },
    isAuthenticated: state => !!state.user,
  },

  actions: {
    setUser(user) {
      this.user = user;
    },
    async handleSubmit() {
      this.loading = true;
      await $.ajax({
        url: `${useStore().appURL}/index.php`,
        type: 'POST',
        xhrFields: {
          withCredentials: true
        },
        data: {
          username: this.username,
          password: this.password,
        },
        success: (data) => {
          if (this.loading) {
            setTimeout(() => {
              this.loading = false;
            }, 1000);
          }
          data = JSON.parse(data);
          this.setUser(data.user);
          router.replace({name: data.user.userType });
        },
        error: (error) => {
          if (this.loading) {
            setTimeout(() => {
              this.loading = false;
              alert(`ERROR ${error.status}: ${error.statusText}`);
            }, 500);
          }
        },
      });
    },
    async signOut() {
      this.signingOut = true;
      await $.ajax({
        url: `${useStore().appURL}/index.php`,
        type: 'POST',
        xhrFields: {
          withCredentials: true
        },
        data: {
          signOut: this.signedOut
        },
        success: (data) => {
          data = JSON.parse(data);
          this.setUser(data.user = null);
          router.push('/');
          this.signingOut = false;
        },
        error: (error) => {
          alert(`ERROR ${error.status}: ${error.statusText}`);
          this.signingOut = false;
        },
      });
    }
  },
});
