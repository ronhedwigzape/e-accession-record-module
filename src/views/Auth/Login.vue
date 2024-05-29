<script setup>
import { ref, reactive } from 'vue';
import { useAuthStore } from '@/stores/store-auth';
import Avatar from '@/components/avatars/Avatar.vue';

</script>

<template>
  <v-row class="tw-min-h-screen tw-flex tw-flex-col sm:tw-justify-center tw-items-center tw-pt-6 sm:tw-pt-0 tw-bg-gray-100">
    <Avatar :height="10" />

    <v-card :elevation="3" class="tw-w-full sm:tw-max-w-md tw-mt-6 tw-px-6 tw-py-4 tw-bg-white tw-overflow-hidden sm:tw-rounded-lg">
      <v-card-text>
        <v-container>
          <v-form @submit.prevent="useAuthStore().handleSubmit">
            <v-row>
              <v-col cols="12" class="pb-2">
                <v-text-field
                  label="Username"
                  variant="outlined"
                  v-model.trim="useAuthStore().username"
                  prepend-inner-icon="mdi-account-circle"
                  :rules="[useAuthStore().rules.required]"
                  :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                ></v-text-field>
              </v-col>
              <v-col cols="12" class="py-2">
                <v-text-field
                  label="Password"
                  variant="outlined"
                  v-model.trim="useAuthStore().password"
                  prepend-inner-icon="mdi-lock"
                  :append-inner-icon="useAuthStore().isPasswordShown ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="useAuthStore().isPasswordShown ? 'text' : 'password'"
                  :rules="[useAuthStore().rules.required]"
                  @click:append-inner="useAuthStore().isPasswordShown = !useAuthStore().isPasswordShown"
                  :density="$vuetify.display.mdAndDown ? 'compact' : 'comfortable'"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col class="tw-flex tw-justify-end" cols="12">
                <v-btn
                  class="!tw-bg-gray-800 !tw-text-white"
                  type="submit"
                  :loading="useAuthStore().loading"
                  :disabled="useAuthStore().loading || !useAuthStore().username || useAuthStore().username.length === 0 || !useAuthStore().password || useAuthStore().password.length === 0"
                >
                  Log in
                </v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
      </v-card-text>
    </v-card>
  </v-row>
</template>

<style scoped>
</style>
