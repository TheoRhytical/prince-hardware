<script setup>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import Modal from '@/Components/Modal.vue';
import '@css/bootstrap.min.css';

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

// Preview uploaded signature
import { signatureImgUrl, fileSignatureSelect } from '@/Composables/CustomerForms.js'
import PhoneNumInputMask from '@/Components/PhoneNumInputMask.vue';

// Edit Customer
import { getModifiedInput } from '@/Composables/CustomerForms.js';
import DeleteRecordModal from './DeleteRecordModal.vue';

const editModalVisible = ref(false)
let customerData = reactive({
	'id': null,
  'full_name': null,
  'date_of_birth': null,
  'address': null,
  'email': null,
  'phone_number': null,
  'signature': null,
})

let unchangedCustomerData;
let currentRow = ref(null)
const editBtn = (row) => {
	currentRow.value = row
	unchangedCustomerData = {
		'id': row.id,
  	'full_name': row.full_name,
  	'date_of_birth': row.date_of_birth,
  	'address': row.address,
  	'email': row.user.email,
  	'phone_number': row.phone_number,
  	'signature': null,
	}
	customerData = reactive(structuredClone(unchangedCustomerData))
	signatureImgUrl.value = row.signature_filename
	console.log("Edit", row, row.id)
	editModalVisible.value = true
}
const debugMessage = ref('')
const editErrorMessage = ref('')
const editSubmitting = ref(false)
const editCustomerSuccessMessage = ref('')
const editSuccessModalVisible = ref(false)
const editCustomer = () => {
	editSubmitting.value = true
	let modifiedInput
	try {
		modifiedInput = getModifiedInput(unchangedCustomerData, customerData)
	} catch (error) {
		editErrorMessage.value = error.message
	}
	console.log('new data', modifiedInput)
	axios.post(route('customer.edit'), modifiedInput,
		{
			...(modifiedInput.signature && 
			{ headers: {'Content-Type': 'multipart/form-data'} }
			)
		},
	)
	.then(res => {
		console.log('Success', res)
		editCustomerSuccessMessage.value = res.data.message
		editSuccessModalVisible.value = true
		editModalVisible.value = false
		if ('signature' in modifiedInput) {
			currentRow.value.signature.src = res.data.customer.signature_filename
		}
		tableRef.value.requestServerInteraction();
	})
	.catch(err => {
		console.log('Error', err)
		editErrorMessage.value = err.response.data.message
		// debugMessage.value = err.response.data
	})
	.finally(() => {
		editSubmitting.value = false
	})
}

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
				<span @click="cardStatusSuccessModalVisible = false" class="close" id="closeUpdateModal">&times;</span>
				<pre>
					{{ cardstatusSuccessMessage }}
				</pre>
			</div>
		</Modal>

  	<Modal
			:show="editStatusModalVisible" 
			max-width='md' 
			@close="editStatusModalVisible = false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
				<span @click="editStatusModalVisible = false" class="close" id="closeUpdateModal">&times;</span>
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
	
		<DeleteRecordModal :to-delete-id="toDeleteId" @deleted="deletedHandler"/>

  	<Modal
			:show="editSuccessModalVisible" 
			max-width='md' 
			@close="editSuccessModalVisible = false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
				<span @click="editSuccessModalVisible = false" class="close" id="closeUpdateModal">&times;</span>
				{{ editCustomerSuccessMessage }}
			</div>
		</Modal>

    <Modal
			:show="editModalVisible" 
			max-width='md' 
			@close="editModalVisible = false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
				<span class="close" id="closeUpdateModal" @click="editModalVisible = false">&times;</span>
				<form 
					id="updateRecordForm"
					@submit.prevent="editCustomer"
				>
					<div v-if="editErrorMessage" class="alert alert-danger" role="alert">
						{{ editErrorMessage }}
					</div>

					<!-- <h4>Update Record</h4> -->
					<label for="updateName">Full Name:</label>
					<input 
						v-model="customerData.full_name"
						type="text" 
						id="updateName" 
					/>
					<br>

					<label for="dateOfBirth">Date of Birth:</label>
					<input 
						v-model="customerData.date_of_birth"
						type="date" 
						id="dateOfBirth" 
					/>
					<br>

					<label for="address">Address:</label>
					<input 
						v-model="customerData.address"
						type="text" 
						id="address" 
					/>
					<br>

					<label for="email">Email:</label>
					<input 
						v-model="customerData.email"
						type="email" 
						id="email" 
					/>
					<br>

					<label for="phone">Phone Number:</label>
      		<PhoneNumInputMask
						:model-value="customerData.phone_number"
        		@update:model-value="(newValue) => customerData.phone_number = newValue"
        		input-class="reg-input"
      		/>

					<label for="signature">Signature (image):</label>
					<input 
        		@input="customerData.signature = $event.target.files[0]"
						type="file" 
						id="signature" 
						accept="image/*" 
        		@change="fileSignatureSelect($event, customerData.signature)"
					/>
					<br>
      		<img v-if="signatureImgUrl" :src="signatureImgUrl" />

					<button type="submit" :disabled="editSubmitting">{{ editSubmitting ? 'Updating... ' : 'Update' }}</button>
				</form>
			</div>
		</Modal>

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
					<q-btn @click="editStatusBtn(props, props.row)">{{ props.row.card_status }}</q-btn>
				</q-td>
			</template>

			<template v-slot:body-cell-actions="props">
				<q-td :props="props">
					<q-btn icon="mode_edit" @click="editBtn(props.row)"></q-btn>
					<q-btn icon="delete" @click="deleteBtn(props.row)"></q-btn>
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


#confirmDelete, #cancelDelete {
	width: 5rem;
	margin: 0 1rem;
}
/* #cancelDelete {

} */
</style>