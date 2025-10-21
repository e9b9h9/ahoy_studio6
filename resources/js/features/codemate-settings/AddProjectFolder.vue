<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    path: ''
});

const handleSubmit = () => {
    if (form.path.trim()) {
        form.post('/codemate/folder-files', {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            }
        });
    }
};
</script>

<template>
<input 
      v-model="form.path"
      @keyup.enter="handleSubmit"
      type="text" 
      placeholder="Enter folder name..."
      class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
      :disabled="form.processing"
    />
</template>

