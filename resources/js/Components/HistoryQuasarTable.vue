<script setup>
import axios from 'axios';
import { ref, onMounted  } from 'vue';

const props = defineProps(['data'])

const columns = [
	{
		name: 'id',
		label: 'ID',
		required: true,
		sortable: true,
		field: 'id',
	},
	{
		name: 'full_name',
		label: 'Full Name',
		required: true,
		sortable: true,
		field: 'full_name',
	},
	{
		name: 'registered_date',
		label: 'Registered Date',
		required: true,
		sortable: true,
		field: 'registered_date',
	},
	{
		name: 'released_date',
		label: 'Release Date',
		required: true,
		sortable: true,
		field: 'released_date',
	},
	{
		name: "actions",
		label: "Action",
	}
]

const tableRef = ref()
const rows = ref(props.data.customers)
const filter = ref('')
const loading = ref(false)
const pagination = ref({
  sortBy: 'desc',
  descending: false,
  page: 1,
  rowsPerPage: props.data.meta.per_page,
  rowsNumber: props.data.meta.total
})

const searchQuery = ref('')

const onRequest = (args) => {
	// console.log('request', args)
	// console.log('query', searchQuery.value)
	loading.value = true
	axios.get('/api/customer/history', {
		params: {
			page: args.pagination.page,
			items: args.pagination.rowsPerPage,
			...(searchQuery.value && {searchQuery: searchQuery.value ?? ''})
		}
	})
	.then((res) => {
		// console.log('axios', res)
		rows.value = res.data.customers;
		pagination.value.page = res.data.meta.current_page;
		pagination.value.rowsPerPage = res.data.meta.per_page;
		pagination.value.rowsNumber = res.data.meta.total;
		loading.value = false
	});
}

const searchRequest = () => {
	tableRef.value.requestServerInteraction();
}

onMounted(() => {
	tableRef.value.requestServerInteraction();
});

</script>

<template>
	<div>
		<div class="search-container">
			<form class="search-group">
				<input v-model="searchQuery" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
				<button @click.prevent="searchRequest" class="btn btn-outline-success" type="submit">Search</button>
			</form>
		</div>
		<q-table
			flat bordered
      ref="tableRef"
      :rows="rows"
      :columns="columns"
      row-key="id"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      binary-state-sort
			:wrap-cells="true"
      @request="onRequest"
		>
			<template v-slot:body-cell-actions="props">
				<q-td :props="props">
					<q-btn icon="mode_edit" ></q-btn>
					<q-btn icon="delete" ></q-btn>
				</q-td>
			</template>
		</q-table>
	</div>
</template>

<style scoped>
#search {
	margin-top: 0.5rem;
	margin-bottom: 0.5rem;
	border: 0.1rem solid #D3D3D3;
}
.search-container {
	display: flex;;
	flex-direction: row-reverse;
	/* width: 50%; */
}
.search-group {
	display: flex;
	/* flex-direction: row-reverse; */
	max-width: 50rem;
}
</style>