<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">
                company Details
            </h2>
            <form @submit.prevent="addOwner">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Add Owner by Email</label>
                    <input type="email" id="email" v-model="newOwnerEmail" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Enter email address">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Add Owner
                </button>
            </form>
            
            <p class="text-center text-gray-600" v-show="loading">Loading company details...</p>
            <div v-if="company">
                <h3 class="text-2xl font-bold mb-4">{{ company.name }}</h3>
                <p class="text-gray-700 mb-4">{{ company.description }}</p>
                <p class="text-gray-700 mb-4">{{ company.website }}</p>
                <p class="text-gray-500">Location: {{ company.location }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
const route = useRoute();
const company = ref(null);
const loading = ref(true);
const newOwnerEmail = ref('');

const fetchCompanyDetails = async () => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/company/${route.params.id}`);
        company.value = response.data.data;
    } catch (error) {
        console.error("Error fetching company details:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCompanyDetails();
});

const addOwner = async () => {
    console.log('newOwnerEmail', newOwnerEmail.value);
    try {
        await axios.get('/sanctum/csrf-cookie');
        // Use the correct endpoint with company ID
        await axios.post(`/api/company/${route.params.id}/add-owner`, { 
            email: newOwnerEmail.value 
        });
        alert('Owner added successfully');
        newOwnerEmail.value = ''; // Clear the input
    } catch (error) {
        console.error("Error adding company owner:", error);
        if (error.response?.data?.message) {
            alert(`Error: ${error.response.data.message}`);
        } else {
            alert('Failed to add owner. Please try again.');
        }
    }
};

</script>