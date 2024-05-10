<script setup>
  import SignOutDialog from "@/components/dialogs/SignOutDialog.vue";

  import {ref} from "vue";
  import {useAuthStore} from "@/stores/store-auth";
const menuOpen = ref(false);

</script>

<template>
  <v-app-bar>
    <v-app-bar-title>Learning Resources and Development Services</v-app-bar-title>

    <v-btn stacked>
      <v-icon>mdi-menu</v-icon>
      <v-menu
        v-model="menuOpen"
        activator="parent"
        :close-on-content-click="false"
      >
        <v-list v-if="useAuthStore().isAuthenticated" width="300" height="130">
          <v-list-item>
        <span class="tw-flex tw-items-center tw-justify-center">
        <v-chip
          class="me-3"
          pill
        >
        <v-avatar start>
        <v-img :src="useAuthStore().getUser.avatar"></v-img>
        </v-avatar>
        {{ useAuthStore().getUser.name }}
        </v-chip>
        </span>
          </v-list-item>
          <v-list-item class="py-2 d-flex justify-center">
            <SignOutDialog/>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-btn>
  </v-app-bar>
</template>

<style scoped>

</style>
