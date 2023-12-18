<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import axios from 'axios';
import { reactive } from 'vue';

DataTable.use(DataTablesCore);

defineOptions({ layout: AuthenticatedLayout });
const props = defineProps(['data']);
console.log('data', props.data)

axios.get('/api/customer/user-info')
	.then((res) => {
		console.log('axios', res)
	});

const dtData = reactive(props.data.customers)
const dtColumns = [
	{data: 'id'},
	{data: 'full_name'},
	{data: 'date_of_birth'},
	{data: 'address'},
	{data: 'user.email'},
	{data: 'phone_number'},
	{data: 'signature_filename',
	render: (data, type, row, meta) => {
		return `<a href='${data}''>test</a>`;
	}},
	{data: 'card_status'}
]
const dtOptions = {
	paging: false
}

</script>

<template>
	<Head title="User Info" />
	<main>
		<h1>User Info</h1>

		<DataTable 
			:columns="dtColumns"
			:options="dtOptions"
			:data="dtData"
			class="display"
			width="100%"
		>
			<thead>
				<tr>
					<th>ID</th>
					<th>Full Name</th>
					<th>Date of Birth</th>
					<th>Address</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Signature</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
		</DataTable>
		<!-- {{ props.data.data }} -->

	</main>
</template>


<style>
@import 'bootstrap';
@import 'datatables.net-bs5';
</style>