<template>
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Find Your Dream Job</h2>
        <form @submit.prevent="submitSearch" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label for="title" class="block text-sm font-medium text-gray-700">Job Title / Keywords</label>
                <input
                    type="text"
                    id="title"
                    v-model="search.search_term"
                    placeholder="PHP Developer"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
            </div>
            <div class="md:col-span-1">
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <input
                    type="text"
                    id="location"
                    v-model="search.location"
                    placeholder="Wien"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
            </div>
            <div class="md:col-span-1 flex items-end">
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Search Jobs
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';

const search = ref({
    search_term: "",
    location: "",
});

const emit = defineEmits(["search-jobs"]);

const submitSearch = () => {
    emit("search-jobs", search.value);
};
</script>

<style scoped>
/*..... */
</style>
