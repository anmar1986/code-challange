<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">
                Job Details
            </h2>
            <p class="text-center text-gray-600" v-show="loading">Loading job details...</p>
            <div v-if="job">
                <h3 class="text-2xl font-bold mb-4">{{ job.title }}</h3>
                <p class="text-gray-700 mb-4">{{ job.description }}</p>
                <p class="text-gray-700 mb-4">{{ job.salary_min }} - {{  job.salary_max }}</p>
                <p class="text-gray-700 mb-4">{{ job.requirements }}</p>
                <p class="text-gray-500">Company: {{ job.company.name }}</p>
                <p class="text-gray-500">Location: {{ job.location }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
const route = useRoute();
const job = ref(null);
const loading = ref(true);
const fetchJobDetails = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/jobs/${route.params.id}`);
        job.value = response.data.data;
    } catch (error) {
        console.error("Error fetching job details:", error);
    } finally {
        loading.value = false;
    }
};
onMounted(() => {
    fetchJobDetails();
});

</script>
 