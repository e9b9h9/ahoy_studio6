<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import ProjectFolderDropdown from '@/features/codemate/ProjectFolderDropdown.vue';
import { useCodemateStore } from '@/features/codemate/codemateStore.js';
import { codemate } from '@/routes';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import CodemateLayout from './CodemateLayout.vue';
import RefreshFiles from '@/features/codemate/RefreshFiles.vue';
import MountFiles from '@/features/codemate/MountFiles.vue';
import ShowAllFolderFiles from '@/features/codemate/ShowAllFolderFiles.vue';
import WatchedFolderTree from '@/features/codemate/WatchedFolderTree.vue';
import MountedFileTree from '@/features/codemate/MountedFileTree.vue';
import ProcessMountedFile from '@/features/codemate/ProcessMountedFile.vue';

const props = defineProps({
    topLevelFolders: {
        type: Array,
        default: () => []
    },
  
});

const store = useCodemateStore();

onMounted(() => {
    store.initializeStore({
        topLevelFolders: props.topLevelFolders
    });
});


const breadcrumbs = [
    {
        title: 'Codemate',
        href: codemate().url,
    },
];
</script>

<template>
    <Head title="Codemate" />

    <AppLayout :breadcrumbs="breadcrumbs">
		<CodemateLayout>
			<template #header-content>
				<ProjectFolderDropdown />
				<RefreshFiles />
				<MountFiles />
				<ShowAllFolderFiles />
			</template>
			
			<template #left-sidebar>
				<WatchedFolderTree />
				<MountedFileTree />
			</template>
			<template #right-sidebar-top>
				<ProcessMountedFile />
			</template>
		</CodemateLayout>
    </AppLayout>
</template>
