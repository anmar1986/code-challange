<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">
                Create a New Company Profile
            </h2>

            <form @submit.prevent="submitCompany">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input
                            type="text"
                            id="name"
                            v-model="company.name"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input
                            type="text"
                            id="location"
                            v-model="company.location"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.location" class="text-red-500 text-xs mt-1">{{ errors.location[0] }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea
                            id="description"
                            v-model="company.description"
                            rows="5"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        ></textarea>
                        <p v-if="errors.description" class="text-red-500 text-xs mt-1">{{ errors.description[0] }}</p>
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website (URL)</label>
                        <input
                            type="url"
                            id="website"
                            v-model="company.website"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.website" class="text-red-500 text-xs mt-1">{{ errors.website[0] }}</p>
                    </div>

                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700">Industry</label>
                        <input
                            type="text"
                            id="industry"
                            v-model="company.industry"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.industry" class="text-red-500 text-xs mt-1">{{ errors.industry[0] }}</p>
                    </div>

                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700">Company Size</label>
                        <input
                            type="text"
                            id="size"
                            v-model="company.size"
                            placeholder="e.g., 1-10, 50-200, 1000+"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.size" class="text-red-500 text-xs mt-1">{{ errors.size[0] }}</p>
                    </div>

                    <div>
                        <label for="founded_year" class="block text-sm font-medium text-gray-700">Founded Year</label>
                        <input
                            type="number"
                            id="founded_year"
                            v-model.number="company.founded_year"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.founded_year" class="text-red-500 text-xs mt-1">{{ errors.founded_year[0] }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="logo_url" class="block text-sm font-medium text-gray-700">Logo URL</label>
                        <input
                            type="url"
                            id="logo_url"
                            v-model="company.logo_url"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.logo_url" class="text-red-500 text-xs mt-1">{{ errors.logo_url[0] }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Social Links (JSON)</label>
                        <textarea
                            id="social_links"
                            v-model="company.social_links_json"
                            rows="3"
                            placeholder="any company"

                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        ></textarea>
                        <p v-if="errors.social_links" class="text-red-500 text-xs mt-1">{{ errors.social_links[0] }}</p>
                    </div>
                </div>

                <div v-if="successMessage" class="mt-6 text-green-600 text-center font-medium">
                    {{ successMessage }}
                </div>

                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Create Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore( );
const company = ref({
    name: "",
    description: "",
    location: "",
    website: null,
    industry: null,
    size: null,
    founded_year: null,
    logo_url: null,
    social_links_json: "{}", // Initialize as JSON string
});

const errors = ref({});
const successMessage = ref(null);

const submitCompany = async () => {
    errors.value = {};
    successMessage.value = null;
    try {
        // Parse social_links_json to object before sending
        const payload = { ...company.value };
        if (payload.social_links_json) {
            try {
                payload.social_links = JSON.parse(payload.social_links_json);
            } catch (e) {
                errors.value.social_links = ["Social links must be a valid JSON string."];
                return;
            }
        }
        delete payload.social_links_json; // Remove the JSON string field

        const response = await axios.post("/api/companies", payload, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });
        successMessage.value = response.data.message;
        // Optionally reset form or redirect
        company.value = {
            name: "",
            description: "",
            location: "",
            website: null,
            industry: null,
            size: null,
            founded_year: null,
            logo_url: null,
            social_links_json: "{}",
        };
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            console.error("Error creating company:", error);
            successMessage.value = "Failed to create company. Please try again.";
        }
    }
};
</script>

<style scoped>
/* Add specific styles for CreateCompany.vue if needed */
</style>
