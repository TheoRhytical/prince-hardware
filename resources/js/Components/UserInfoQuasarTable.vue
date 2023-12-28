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
		name: 'date_of_birth',
		label: 'Date of Birth',
		required: true,
		sortable: true,
		field: 'date_of_birth',
	},
	{
		name: 'address',
		label: 'Address',
		required: true,
		sortable: true,
		field: 'address',
		classes: 'address-width',
		headerClasses: 'address-width',
	},
	{
		name: 'user.email',
		label: 'Email',
		required: true,
		sortable: true,
		field: row => row.user.email,
	},
	{
		name: 'phone_number',
		label: 'Phone Number',
		required: true,
		sortable: true,
		field: 'phone_number',
	},
	{
		name: 'signature',
		label: 'Signature',
		required: true,
		sortable: true,
		field: 'signature_filename',
		classes: 'signature-width',
		headerClasses: 'signature-width',
		// format: data => `<a href='${data}'>test</a>`,
	},
	{
		name: 'card_status',
		label: 'Status',
		required: true,
		sortable: true,
		field: 'card_status',
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

const onRequest = (args) => {
	console.log('request', args)
	loading.value = true
	axios.get('/api/customer/user-info', {
		params: {
			page: args.pagination.page,
			items: args.pagination.rowsPerPage,
		}
	})
	.then((res) => {
		console.log('axios', res)
		rows.value = res.data.customers;
		pagination.value.page = res.data.meta.current_page;
		pagination.value.rowsPerPage = res.data.meta.per_page;
		pagination.value.rowsNumber = res.data.meta.total;
		loading.value = false
	});
}

const searchRequest = () => {

}

onMounted(() => {
	tableRef.value.requestServerInteraction();
});

</script>

<template>
	<div>
		<div class="search-container">
			<form class="search-group">
				<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
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
        <q-input v-model="filter" placeholder="Search" />
				<template v-slot:body-cell-signature="props">
					<q-td :props="props">
						<img :src="props.row.signature_filename" />
					</q-td>
				</template>
				<template v-slot:body-cell-actions="props">
					<q-td :props="props">
						<q-btn icon="mode_edit" ></q-btn>
						<q-btn icon="delete" ></q-btn>
					</q-td>
				</template>
          <!-- <temp25rem v-slot:append>
            <q-icon name="search" />
          </temp25rem late> -->
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

<style>
.address-width {
	max-width: 15rem !important;
	/* background-color: red !important; */
}

.signature-width {
	max-width: 10rem !important;
}
</style>