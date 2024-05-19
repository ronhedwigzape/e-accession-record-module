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
                        :disabled="signingOut"
                    >
                        Go Back
                    </v-btn>
                    <v-btn
                        color="red"
                        variant="tonal"
                        @click="signOut"
                        :loading="signingOut"
                    >
                        Sign Out
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-btn>
</template>

<script setup>
import $ from "jquery";
import {ref} from "vue";
import {useRouter} from "vue-router";
import {useAuthStore} from "@/stores/store-auth";
import {useStore} from "@/stores/store";

const dialog = ref(false);
const dialog1 = ref(false);
const signingOut = ref(false);
const signedOut = ref(false);

const store = useStore();
const authStore = useAuthStore();
const router = useRouter();

const signOut = async () => {
    signingOut.value = true;
    await $.ajax({
        url: `${store.appURL}/index.php`,
        type: 'POST',
        xhrFields: {
            withCredentials: true
        },
        data: {
            signOut: signedOut.value
        },
        success: (data) => {
            data = JSON.parse(data);
            authStore.setUser(data.user = null);
            router.push('/');
            signingOut.value = false;
        },
        error: (error) => {
            alert(`ERROR ${error.status}: ${error.statusText}`);
            signingOut.value = false;
        },
    });
};

</script>

<style scoped>

</style>
