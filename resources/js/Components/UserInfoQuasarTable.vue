<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import Modal from '@/Components/Modal.vue';
import '@fortawesome/fontawesome-free/css/all.min.css';
import EditCustomerInfoModal from '@/Components/EditCustomerInfoModal.vue'
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
	console.log('request', args)
	console.log('query', searchQuery.value)
	loading.value = true
	axios.get('/api/customer/user-info', {
		params: {
			page: args.pagination.page,
			items: args.pagination.rowsPerPage,
			...(searchQuery.value && {searchQuery: searchQuery.value ?? ''})
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

// Setup
onMounted(() => {
	tableRef.value.requestServerInteraction();
});

// Search feature
const searchQuery = ref('')
const searchRequest = () => {
	tableRef.value.requestServerInteraction();
}

// Edit Customer
import { openEditInfoModal } from '@/Composables/CustomerForms.js'

// Delete customer
const toDeleteId = ref(null)
const deleteBtn = (row) => {
	toDeleteId.value = row.id
	console.log('deleteId', toDeleteId.value)
}
const deletedHandler = () => {
	toDeleteId.value = null
	tableRef.value.requestServerInteraction();
}

// Edit Customer Card Status
const editStatusModalVisible = ref(false)
const currentCustomerCardStatus = ref('')
const customerCardStatusInputs = reactive({
	id: null,
	card_status: null
})
const cardStatusSuccessModalVisible = ref(false)
const cardstatusSuccessMessage = ref('')
const editStatusBtn = (props, row) => {
	editStatusModalVisible.value = true
	currentCustomerCardStatus.value = row.card_status
	customerCardStatusInputs.card_status = row.card_status
	customerCardStatusInputs.id = row.id
	console.log('status', props)
}
const cardStatusErrorMessage = ref('')
const updateCardStatus = () => {
	console.log(currentCustomerCardStatus.value, customerCardStatusInputs.card_status, currentCustomerCardStatus.value === customerCardStatusInputs.card_status)
	const oldValue = currentCustomerCardStatus.value === "On Process" ? "processing" : "released";
	if (oldValue === customerCardStatusInputs.card_status) {
		cardStatusErrorMessage.value = "No value changed"
		return
	}
	console.log('card status params', customerCardStatusInputs)
	axios.patch(route('customer.card-status'), customerCardStatusInputs)
	.then(res => {
		console.log('card status success', res)
		cardstatusSuccessMessage.value = res.data.message
		editStatusModalVisible.value = false
		cardStatusSuccessModalVisible.value = true
		cardStatusErrorMessage.value = ''
		tableRef.value.requestServerInteraction();
	})
	.catch(err => {
		console.log('card status err', err)
		if (err.response.status === 500) {
			cardStatusErrorMessage.value = err.response.data
		} else {
			cardStatusErrorMessage.value = err.response.data.message
		}
	})
	.finally(() => {
		currentCustomerCardStatus.value = ''
		customerCardStatusInputs.card_status = ''
	})
}

</script>

<template>
	<div>
  	<Modal
			:show="cardStatusSuccessModalVisible" 
			max-width='md' 
			@close="cardStatusSuccessModalVisible = false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
				{{ cardstatusSuccessMessage }}
			</div>
		</Modal>

  	<Modal
			:show="editStatusModalVisible" 
			max-width='md' 
			@close="editStatusModalVisible = false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
				<div class="alert alert-danger" v-if="cardStatusErrorMessage">{{ cardStatusErrorMessage }}</div>
				<p>Current Status: {{ currentCustomerCardStatus }}</p>
				<label for="newStatus">New Status:</label>
				<select v-model="customerCardStatusInputs.card_status">
					  <option value="released">Released</option>
					  <option value="processing">On Process</option>
					  <!-- Add other status options as needed -->
				</select>
				<button @click="updateCardStatus()">Update Status</button>
			</div>
		</Modal>
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
						class="bg-blue-400 rounded-md px-4 py-2 border-none text-white flex justify-center"
						@click="editStatusBtn(props, props.row)"
					>
						{{ props.row.card_status }}
					</button>
					<!-- <q-btn @click="editStatusBtn(props, props.row)" class="bg-blue-400 rounded-md">{{ props.row.card_status }}</q-btn> -->
				</q-td>
			</template>

			<template v-slot:body-cell-actions="props">
				<q-td :props="props" text-color="white">
					<!-- <q-btn>
					</q-btn>
					<q-btn>
					</q-btn> -->
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
	@apply text-white;
	.edit-btn {
		@apply bg-blue-400 #{!important};
	}
	.delete-btn {
		@apply bg-red-500 #{!important};
	}
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

/* .email-width {
	max-width: 10rem !important;
} */

.signature-width {
	max-width: 10rem !important;
}

.signature-width > img{
	width: 100%;
}

#confirmDelete, #cancelDelete {
	width: 5rem;
	margin: 0 1rem;
}
/* #cancelDelete {

} */
</style>