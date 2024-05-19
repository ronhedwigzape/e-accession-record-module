<script setup>
import SignOutDialog from "@/components/dialogs/SignOutDialog.vue";
import { ref, onMounted, onUnmounted } from "vue";
import { useAuthStore } from "@/stores/store-auth";

const menuOpen = ref(false);
const currentTime = ref("");

const updateTime = () => {
  const options = { timeZone: "Asia/Manila", hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric', weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  currentTime.value = new Date().toLocaleString("en-US", options);
};

onMounted(() => {
  updateTime();
  const interval = setInterval(updateTime, 1000);
  onUnmounted(() => clearInterval(interval));
});
</script>

<template>
  <v-app-bar image="/cspc-nabua.jpg" :elevation="1">
    <template v-slot:image>
      <v-img
        gradient="to top right, rgba(19,84,122,.9), rgba(60,62,208,.5)"
      ></v-img>
    </template>
    <v-app-bar-title class="">
      <p class="font-weight-bold text-white">Learning Resources and Development Services</p>
      <p class="text-uppercase font-weight-bold text-blue-lighten-1">E-Accession Record Module</p>
    </v-app-bar-title>

    <v-app-bar-title class="text-white text-end mr-3">
      {{ currentTime }}
    </v-app-bar-title>

    <v-btn stacked>
      <v-icon class="text-white">mdi-menu</v-icon>
      <v-menu
        v-model="menuOpen"
        activator="parent"
        :close-on-content-click="false"
      >
        <v-list v-if="useAuthStore().isAuthenticated" width="300" height="130">
          <v-list-item>
            <span class="tw-flex tw-items-center tw-justify-center">
              <v-chip class="me-3" pill>
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
