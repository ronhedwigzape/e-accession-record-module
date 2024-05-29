<template>
    <v-btn
        prepend-icon="mdi-logout"
        class="px-16 text-red-darken-3 text-uppercase !tw-font-bold"
        variant="tonal"
    >
        Sign out
        <v-dialog
            activator="parent"
            v-model="dialog1"
            max-width="400"
        >
            <v-card>
                <v-card-title class="bg-black !tw-font-bold">
                    <v-icon id="remind">mdi-alert-circle</v-icon>
                    Confirm Sign Out
                </v-card-title>
                <v-card-text>Are you sure you want to sign out?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green"
                        variant="text"
                        @click="dialog1 = false"
                        :disabled="useAuthStore().signingOut"
                    >
                        Go Back
                    </v-btn>
                    <v-btn
                        color="red"
                        variant="tonal"
                        @click="useAuthStore().signOut"
                        :loading="useAuthStore().signingOut"
                    >
                        Sign Out
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-btn>
</template>

<script setup>
import {ref} from "vue";
import {useAuthStore} from "@/stores/store-auth";

const dialog = ref(false);
const dialog1 = ref(false);

</script>

<style scoped>

</style>
