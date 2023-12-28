<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { reactive } from 'vue';
import UserInfoQuasarTable from '@/Components/UserInfoQuasarTable.vue';


defineOptions({ layout: AuthenticatedLayout });
const props = defineProps(['data']);
// console.log('data', props.data)

axios.get('/api/customer/user-info')
	.then((res) => {
		console.log('axios', res)
	});


</script>

<template>
	<Head title="User Info" />
	<main>
		<h1>User Info</h1>
		<UserInfoQuasarTable :data="data" />
		<!-- {{ props.data.data }} -->

	</main>
</template>


<style>
@import 'bootstrap';
</style>