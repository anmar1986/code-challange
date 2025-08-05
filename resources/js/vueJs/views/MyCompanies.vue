<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">
                My Companies
            </h2>

            <div class="mb-6 text-right">
                <router-link to="/create-company" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create New Company
                </router-link>
            </div>

            <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="n in 3" :key="n" class="bg-white p-6 rounded-lg shadow-md animate-pulse">
                    <div class="h-6 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded w-full mb-4"></div>
                    <div class="h-10 bg-gray-200 rounded w-full"></div>
                </div>
            </div>

            <div v-else-if="companies.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="company in companies" :key="company.id" class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <img v-if="company.logo_url" :src="company.logo_url" alt="Company Logo" class="h-16 w-16 object-contain rounded-full border border-gray-200 mr-4" />
                        <div v-else class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-xl font-semibold mr-4">{{ company.name.charAt(0) }}</div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ company.name }}</h3>
                            <p class="text-gray-600 text-sm">{{ company.location }}</p>
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm line-clamp-3 mb-4">{{ company.description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Jobs Posted: {{ company.jobs_count }}</span>
                        <router-link :to="`/companies/${company.id}`" class="text-blue-600 hover:underline text-sm">View Details</router-link>
                    </div>
                </div>
            </div>

            <div v-else class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-600">You haven't created any companies yet.</p>
                <router-link to="/create-company" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Your First Company
                </router-link>
            </div>

            <!-- Pagination -->
            <div v-if="companies.length > 0 && pagination.last_page > 1" class="mt-6">
                <Pagination :pagination="pagination" @page-changed="fetchMyCompanies" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';
import Pagination from '../components/Pagination.vue'; // Assuming you reuse the Pagination component

const authStore = useAuthStore();
const companies = ref([]);
const loading = ref(true);
const pagination = ref({});

const fetchMyCompanies = async (page = 1) => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/my-companies?page=${page}`, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });
        companies.value = response.data.data.data;
        pagination.value = {
            current_page: response.data.data.current_page,
            last_page: response.data.data.last_page,
            total: response.data.data.total,
        };
    } catch (error) {
        console.error("Error fetching my companies:", error);
        companies.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchMyCompanies();
});
</script>

<style scoped>
/* Add specific styles for MyCompanies.vue if needed */
</style>
