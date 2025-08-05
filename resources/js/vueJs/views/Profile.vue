<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">
                User Profile
            </h2>

            <div v-if="authStore.user" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-lg text-gray-900">{{ authStore.user.name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-lg text-gray-900">{{ authStore.user.email }}</p>
                </div>
                <!-- Add more profile fields as needed -->
            </div>
            <div v-else class="text-center text-gray-600">
                <p>Please log in to view your profile.</p>
                <router-link to="/login" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Login
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore();
</script>

<style scoped>
/* Add specific styles for Profile.vue if needed */
</style>
