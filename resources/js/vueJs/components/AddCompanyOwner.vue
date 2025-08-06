<template>
  <div class="bg-white p-4 rounded shadow mt-6">
    <h3 class="text-lg font-semibold mb-2">Add Company Owner</h3>
    <form @submit.prevent="addOwner">
      <div class="flex items-center gap-2">
        <input v-model="userId" type="text" placeholder="Enter user ID" class="border rounded px-2 py-1" required />
        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Add Owner</button>
      </div>
    </form>
    <p v-if="message" :class="{'text-green-600': success, 'text-red-600': !success}" class="mt-2">{{ message }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
const props = defineProps({ companyId: { type: [Number, String], required: true } });
const userId = ref('');
const message = ref('');
const success = ref(false);

const addOwner = async () => {
  message.value = '';
  success.value = false;
  try {
    await axios.post(`/api/companies/${props.companyId}/add-owner`, { user_id: userId.value });
    message.value = 'Owner added successfully!';
    success.value = true;
    userId.value = '';
  } catch (e) {
    message.value = e.response?.data?.message || 'Failed to add owner.';
    success.value = false;
  }
};
</script>
