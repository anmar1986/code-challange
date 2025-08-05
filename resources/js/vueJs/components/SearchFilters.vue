<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Filter Jobs</h2>
        <form @submit.prevent="applyFilters">
            <!-- or i could implemnt other search filters .....-->
            <div class="mb-4">
                <label for="employment_type" class="block text-sm font-medium text-gray-700">Employment Type</label>
                <select
                    id="employment_type"
                    v-model="filters.employment_type"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                    <option value="">Any</option>
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="contract">Contract</option>
                    <option value="internship">Internship</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="experience_level" class="block text-sm font-medium text-gray-700">Experience Level</label>
                <select
                    id="experience_level"
                    v-model="filters.experience_level"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                    <option value="">Any</option>
                    <option value="entry">Entry Level</option>
                    <option value="mid">Mid Level</option>
                    <option value="senior">Senior Level</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="salary_min" class="block text-sm font-medium text-gray-700">Minimum Salary</label>
                <input
                    type="number"
                    id="salary_min"
                    v-model.number="filters.salary_min"
                    placeholder="e.g., 50000"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
            </div>

            <div class="mb-6">
                <label for="salary_max" class="block text-sm font-medium text-gray-700">Maximum Salary</label>
                <input
                    type="number"
                    id="salary_max"
                    v-model.number="filters.salary_max"
                    placeholder="e.g., 100000"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Apply Filters
            </button>
            <button
                type="button"
                @click="clearFilters"
                class="w-full mt-2 bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
            >
                Clear Filters
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';

const filters = ref({
    employment_type: "",
    experience_level: "",
    salary_min: null,
    salary_max: null,
});

const emit = defineEmits(["filter-jobs"]);

const applyFilters = () => {
    emit("filter-jobs", filters.value);
};

const clearFilters = () => {
    filters.value = {
        employment_type: "",
        experience_level: "",
        salary_min: null,
        salary_max: null,
    };
    emit("filter-jobs", filters.value);
};
</script>

<style scoped>
/* styling*/
</style>
