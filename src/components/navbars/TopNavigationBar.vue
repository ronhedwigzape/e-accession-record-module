<script setup>
import SignOutDialog from "@/components/dialogs/SignOutDialog.vue";
import { ref, onMounted, onUnmounted, computed } from "vue";
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

const formattedTime = computed(() => {
  const parts = currentTime.value.split(' at ');
  const datePart = parts[0];
  const timePart = parts[1];
  const [time, period] = timePart.split(' ');

  return `<strong>${datePart}</strong> at <strong>${time}</strong> ${period}`;
});
</script>

<template>
  <v-app-bar image="/cspc-nabua.jpg">
    <template v-slot:prepend>
      <v-app-bar-nav-icon :ripple="false">
        <v-icon size="x-large" class="text-white">mdi-book-account</v-icon>
      </v-app-bar-nav-icon>
    </template>
    <template v-slot:image>
      <v-img
        gradient="to top right, rgba(19,84,122,.9), rgba(60,62,208,.5)"
      ></v-img>
    </template>
    <v-app-bar-title>
      <p class="font-weight-bold text-white">Learning Resources and Development Services</p>
      <p class="text-uppercase font-weight-bold text-blue-lighten-1">E-Accession Record Module</p>
    </v-app-bar-title>

    <v-app-bar-title class="text-white text-end mr-3">
      <span v-html="formattedTime"></span>
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
