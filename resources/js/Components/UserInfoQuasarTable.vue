<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import '@fortawesome/fontawesome-free/css/all.min.css';
import EditCustomerCardStatusModal from '@/Components/EditCustomerCardStatusModal.vue';
import { openEditStatusBtn } from '@/Composables/CustomerCardStatus.js';
import EditCustomerInfoModal from '@/Components/EditCustomerInfoModal.vue'
import { openEditInfoModal } from '@/Composables/CustomerForms.js'
import DeleteRecordModal from '@/Components/DeleteRecordModal.vue';

const props = defineProps(['data'])

// User info table
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
		field: 'date_of_birth_formatted',
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
	},
	{
		name: 'card-status',
		label: 'Status',
		required: true,
		sortable: true,
		field: 'card_status',
	},
	{
		name: 'actions',
		label: 'Action',
		classes: 'action-col'
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
	// console.log('request', args)
	// console.log('query', searchQuery.value)
	loading.value = true
	axios.get('/api/customer/user-info', {
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

// Setup
onMounted(() => {
	tableRef.value.requestServerInteraction();
});

// Search feature
const searchQuery = ref('')
const searchRequest = () => {
	tableRef.value.requestServerInteraction();
}

// Delete customer
const toDeleteId = ref(null)
const deleteBtn = (row) => {
	toDeleteId.value = row.id
	// console.log('deleteId', toDeleteId.value)
}
const deletedHandler = () => {
	toDeleteId.value = null
	tableRef.value.requestServerInteraction();
}


</script>

<template>
	<div>
		<EditCustomerCardStatusModal 
			@updated-customer-card-status="tableRef.requestServerInteraction()"
		/>
		<EditCustomerInfoModal 
			@updated-customer="tableRef.requestServerInteraction()"
		/>
		<DeleteRecordModal :to-delete-id="toDeleteId" @deleted="deletedHandler"/>

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
      <!-- <q-input v-model="filter" placeholder="Search" /> -->
			<template v-slot:body-cell-signature="props">
				<q-td :props="props">
					<img :src="props.row.signature_filename" />
				</q-td>
			</template>

			<template v-slot:body-cell-card-status="props">
				<q-td :props="props">
					<button 
						class="bg-blue-400 px-4 py-2 flex justify-center"
						@click="openEditStatusBtn(props.row)"
					>
						{{ props.row.card_status }}
					</button>
				</q-td>
			</template>

			<template v-slot:body-cell-actions="props">
				<q-td :props="props" text-color="white">
					<q-btn @click="openEditInfoModal(props.row)" class="edit-btn">
    				<i class="fas fa-pen"></i>
					</q-btn>
					<q-btn @click="deleteBtn(props.row)" class="delete-btn">
    				<i class="fas fa-trash"></i>
					</q-btn>
				</q-td>
			</template>

		</q-table>
	</div>
</template>

<style lang="scss">
.action-col {
	// @apply text-white;
	.edit-btn {
		@apply bg-blue-400 #{!important};
	}
	.delete-btn {
		@apply bg-red-500 #{!important};
	}
}

button {
	@apply text-white rounded-md border-none #{!important};
}
</style>

<style scoped>
#search {
	margin-top: 0.5rem;
	margin-bottom: 0.5rem;
	border: 0.1rem solid #D3D3D3;
}
.search-container {
	display: flex;
	flex-direction: row-reverse;
	margin-bottom: 0.25rem;
	/* width: 50%; */
}
.search-group {
	display: flex;
	max-width: 50rem;
}

</style>

<style>
.address-width {
	max-width: 15rem !important;
}

.signature-width {
	max-width: 10rem !important;
}

.signature-width > img{
	width: 100%;
}

</style>