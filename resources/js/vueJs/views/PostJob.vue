<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">
                Post a New Job
            </h2>

            <form @submit.prevent="submitJob">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>
                        <select
                            id="company_id"
                            v-model="job.company_id"
                            required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                        >
                            <option value="">Select a company</option>
                            <option v-for="company in companies" :key="company.id" :value="company.id">
                                {{ company.name }}
                            </option>
                        </select>
                        <p v-if="errors.company_id" class="text-red-500 text-xs mt-1">{{ errors.company_id[0] }}</p>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                        <input
                            type="text"
                            id="title"
                            v-model="job.title"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title[0] }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea
                            id="description"
                            v-model="job.description"
                            rows="5"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        ></textarea>
                        <p v-if="errors.description" class="text-red-500 text-xs mt-1">{{ errors.description[0] }}</p>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input
                            type="text"
                            id="location"
                            v-model="job.location"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.location" class="text-red-500 text-xs mt-1">{{ errors.location[0] }}</p>
                    </div>

                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700">Employment Type</label>
                        <select
                            id="employment_type"
                            v-model="job.employment_type"
                            required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                        >
                            <option value="full-time">Full-time</option>
                            <option value="part-time">Part-time</option>
                            <option value="contract">Contract</option>
                            <option value="internship">Internship</option>
                        </select>
                        <p v-if="errors.employment_type" class="text-red-500 text-xs mt-1">{{ errors.employment_type[0] }}</p>
                    </div>

                    <div>
                        <label for="experience_level" class="block text-sm font-medium text-gray-700">Experience Level</label>
                        <input
                            type="text"
                            id="experience_level"
                            v-model="job.experience_level"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.experience_level" class="text-red-500 text-xs mt-1">{{ errors.experience_level[0] }}</p>
                    </div>

                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-gray-700">Minimum Salary</label>
                        <input
                            type="number"
                            id="salary_min"
                            v-model.number="job.salary_min"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.salary_min" class="text-red-500 text-xs mt-1">{{ errors.salary_min[0] }}</p>
                    </div>

                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-gray-700">Maximum Salary</label>
                        <input
                            type="number"
                            id="salary_max"
                            v-model.number="job.salary_max"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.salary_max" class="text-red-500 text-xs mt-1">{{ errors.salary_max[0] }}</p>
                    </div>

                    <div>
                        <label for="salary_currency" class="block text-sm font-medium text-gray-700">Salary Currency</label>
                        <input
                            type="text"
                            id="salary_currency"
                            v-model="job.salary_currency"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.salary_currency" class="text-red-500 text-xs mt-1">{{ errors.salary_currency[0] }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements</label>
                        <textarea
                            id="requirements"
                            v-model="job.requirements"
                            rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        ></textarea>
                        <p v-if="errors.requirements" class="text-red-500 text-xs mt-1">{{ errors.requirements[0] }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="benefits" class="block text-sm font-medium text-gray-700">Benefits</label>
                        <textarea
                            id="benefits"
                            v-model="job.benefits"
                            rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        ></textarea>
                        <p v-if="errors.benefits" class="text-red-500 text-xs mt-1">{{ errors.benefits[0] }}</p>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700">Expires At</label>
                        <input
                            type="date"
                            id="expires_at"
                            v-model="job.expires_at"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <p v-if="errors.expires_at" class="text-red-500 text-xs mt-1">{{ errors.expires_at[0] }}</p>
                    </div>

                    <div class="flex items-center">
                        <input
                            id="is_active"
                            name="is_active"
                            type="checkbox"
                            v-model="job.is_active"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active Job Listing</label>
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
                        Post Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore();
const job = ref({
    company_id: "",
    title: "",
    description: "",
    location: "",
    employment_type: "full-time",
    experience_level: null,
    salary_min: null,
    salary_max: null,
    salary_currency: "EUR",
    requirements: null,
    benefits: null,
    expires_at: null,
    is_active: true,
});

const companies = ref([]);
const errors = ref({});
const successMessage = ref(null);

const fetchMyCompanies = async () => {
    try {
        const response = await axios.get("/api/my-companies", {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });
        companies.value = response.data.data.data; // Adjust based on your API response structure
    } catch (error) {
        console.error("Error fetching companies:", error);
    }
};

const submitJob = async () => {
    errors.value = {};
    successMessage.value = null;
    try {
        const response = await axios.post("/api/jobs", job.value, {
            headers: {
                Authorization: `Bearer ${authStore.token}`,
            },
        });
        successMessage.value = response.data.message;
        // Optionally reset form or redirect
        job.value = {
            company_id: "",
            title: "",
            description: "",
            location: "",
            employment_type: "full-time",
            experience_level: null,
            salary_min: null,
            salary_max: null,
            salary_currency: "EUR",
            requirements: null,
            benefits: null,
            expires_at: null,
            is_active: true,
        };
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            console.error("Error posting job:", error);
            successMessage.value = "Failed to post job. Please try again.";
        }
    }
};

onMounted(() => {
    fetchMyCompanies();
});
</script>

<style scoped>
/* Add specific styles for PostJob.vue if needed */
</style>
