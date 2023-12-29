<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import HistoryQuasarTable from '@/Components/HistoryQuasarTable.vue';

defineOptions({ layout: AuthenticatedLayout })
const props = defineProps(['data'])
</script>


<template>
	<Head title="History" />
	<main style="padding: 2rem 1rem">
		<HistoryQuasarTable :data="data" />
	</main>
</template>