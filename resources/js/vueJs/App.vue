<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();

onMounted(() => {
    authStore.fetchUser(); // Fetch user data when the app mounts
});
</script>

<style>
/* styling Global styles can go here or in app.css */
</style>
