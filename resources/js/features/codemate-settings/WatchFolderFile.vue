<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    folderFile: {
        type: Object,
        required: true
    }
});

const handleChange = (event) => {
    if (event.target.checked) {
        router.put(`/codemate/folder-files/${props.folderFile.id}`, {
            watch: true
        }, {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <input 
        type="checkbox" 
        :checked="folderFile.watch === true"
        @change="handleChange"
        class="mr-2"
    />
</template>