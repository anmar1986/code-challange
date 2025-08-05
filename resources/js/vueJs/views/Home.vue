<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Header/Navigation (Optional, can be a separate component later) -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Code challenge!</h1>
                <nav>
                    <router-link to="/login" class="text-blue-600 hover:text-blue-800 mr-4">Login</router-link>
                    <router-link to="/register" class="text-blue-600 hover:text-blue-800">Register</router-link>
                    <router-link v-if="authStore.isAuthenticated" to="/post-job" class="ml-4 text-blue-600 hover:text-blue-800">Post Job</router-link>
                    <router-link v-if="authStore.isAuthenticated" to="/my-companies" class="ml-4 text-blue-600 hover:text-blue-800">My Companies</router-link>
                    <button v-if="authStore.isAuthenticated" @click="logout" class="ml-4 text-red-600 hover:text-red-800">Logout</button>
                </nav>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Search Form Component -->
                <SearchForm @search-jobs="handleSearch" />

                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Job Listings -->
                    <div class="md:col-span-2">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Available Jobs</h2>
                        <div v-if="loading" class="grid grid-cols-1 gap-4">
                            <JobCardSkeleton v-for="n in 3" :key="n" />
                        </div>
                        <div v-else-if="jobs.length > 0" class="grid grid-cols-1 gap-4">
                            <JobCard v-for="job in jobs" :key="job.id" :job="job" />
                        </div>
                        <div v-else class="bg-white p-6 rounded-lg shadow">
                            <p class="text-gray-600 text-center">No jobs found matching your criteria.</p>
                        </div>

                        <!-- Pagination -->
                        <div v-if="jobs.length > 0 && pagination.last_page > 1" class="mt-6">
                            <Pagination :pagination="pagination" @page-changed="fetchJobs" />
                        </div>
                    </div>

                    <!-- Search Filters (Sidebar) -->
                    <div class="md:col-span-1">
                        <SearchFilters @filter-jobs="handleFilter" />
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';
import SearchForm from '../components/SearchForm.vue';
import JobCard from '../components/JobCard.vue';
import JobCardSkeleton from '../components/JobCardSkeleton.vue'; // Corrected path
import Pagination from '../components/Pagination.vue';
import SearchFilters from '../components/SearchFilters.vue'; // Corrected path


const authStore = useAuthStore();
const jobs = ref([]);
const loading = ref(true);
const pagination = ref({});
const currentSearchParams = ref({});

const fetchJobs = async (page = 1) => {
    loading.value = true;
    try {
        const params = { ...currentSearchParams.value, page };
        const response = await axios.get('/api/search', { params });
        jobs.value = response.data.data.data; // Adjust based on your API response structure
        pagination.value = {
            current_page: response.data.data.current_page,
            last_page: response.data.data.last_page,
            total: response.data.data.total,
        };
    } catch (error) {
        console.error('Error fetching jobs:', error);
        jobs.value = [];
    } finally {
        loading.value = false;
    }
};

const handleSearch = (params) => {
    currentSearchParams.value = { ...currentSearchParams.value, ...params };
    fetchJobs(1);
};

const handleFilter = (filters) => {
    currentSearchParams.value = { ...currentSearchParams.value, ...filters };
    fetchJobs(1);
};

const logout = async () => {
    await authStore.logout();
    // Optionally redirect to login or home after logout
    // router.push({ name: 'Login' });
};

onMounted(() => {
    fetchJobs();
});
</script>

<style scoped>
/* Add specific styles for Home.vue if needed */
</style>
