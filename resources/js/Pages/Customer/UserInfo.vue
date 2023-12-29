<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { reactive } from 'vue';
import UserInfoQuasarTable from '@/Components/UserInfoQuasarTable.vue';

defineOptions({ layout: AuthenticatedLayout });
const props = defineProps(['data']);
</script>

<template>
	<Head title="User Info" />
	<main style="padding: 2rem 1rem">
		<UserInfoQuasarTable :data="data" />
		<!-- {{ props.data.data }} -->

	</main>
</template>


<style>
@import 'bootstrap';
</style>