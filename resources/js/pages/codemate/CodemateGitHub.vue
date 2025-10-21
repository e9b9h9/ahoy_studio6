<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { codemateGithub } from '@/routes';
import { Head } from '@inertiajs/vue3';
import CodemateLayout from './CodemateLayout.vue';
import ProjectDirectoryAddButon from '@/features/codemateGitHub/ProjectDirectoryAddButon.vue';

const breadcrumbs = [
    {
        title: 'Codemate GitHub',
        href: codemateGithub().url,
    },
];
</script>

<template>
    <Head title="Codemate GitHub" />

    <AppLayout :breadcrumbs="breadcrumbs">
		<CodemateLayout>
			<template #header-content>
				<ProjectDirectoryAddButon />
			</template>
			
			<template #left-sidebar>
					
			</template>
			<template #right-sidebar-top>
				
			</template>
		</CodemateLayout>
    </AppLayout>
</template>
