<template>
    <div class="bg-white p-6 rounded-lg shadow-md flex items-start space-x-4">
        <div class="flex-shrink-0">
            <img v-if="job.company.logo_url" :src="job.company.logo_url" alt="Company Logo" class="h-16 w-16 object-contain rounded-full border border-gray-200" />
            <div v-else class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-xl font-semibold">{{ job.company.name.charAt(0) }}</div>
        </div>
        <div class="flex-grow">
            <h3 class="text-xl font-semibold text-gray-900">{{ job.title }}</h3>
            <p class="text-blue-600 text-lg">{{ job.company.name }}</p>
            <p class="text-gray-600 text-sm mt-1">{{ job.location }} &bull; {{ job.employment_type }}</p>
            <p v-if="job.salary_min || job.salary_max" class="text-gray-700 text-sm mt-1">
                Salary: {{ formatSalary(job.salary_min, job.salary_max, job.salary_currency) }}
            </p>
            <p class="text-gray-700 text-sm mt-2 line-clamp-3">{{ job.description }}</p>
            <div class="mt-4 flex items-center space-x-4">
                <router-link :to="`/jobs/${job.id}`" class="text-blue-600 hover:underline text-sm">View Details</router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    job: {
        type: Object,
        required: true,
    },
});

const formatSalary = (min, max, currency) => {
    if (min && max) {
        return `${currency} ${min} - ${max}`;
    } else if (min) {
        return `${currency} ${min}+`;
    } else if (max) {
        return `Up to ${currency} ${max}`;
    }
    return "Not specified";
};
</script>

<style scoped>
/** i will optimize it if i need ! */
</style>
