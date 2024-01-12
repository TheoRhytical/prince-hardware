<script setup>
// Edit Customer Card Status
import { ref } from 'vue';
import {
	cardStatusErrorMessage,
	currentCustomerCardStatus,
	customerCardStatusInputs,
	editStatusModalVisible,
} from '@/Composables/CustomerCardStatus.js'
import Modal from './Modal.vue';
import axios from 'axios';

const emit = defineEmits(['updatedCustomerCardStatus'])

const submitting = ref(false)
const cardStatusSuccessModalVisible = ref(false)
const cardstatusSuccessMessage = ref('')

const updateCardStatus = () => {
	// console.log(currentCustomerCardStatus.value, customerCardStatusInputs.card_status, currentCustomerCardStatus.value === customerCardStatusInputs.card_status)
	submitting.value = true
	if (currentCustomerCardStatus.value === customerCardStatusInputs.card_status) {
		cardStatusErrorMessage.value = "No value changed"
		submitting.value = false
		return
	}

	// console.log('card status params', customerCardStatusInputs)
	axios.patch(route('customer.card-status'),
	{
		id: customerCardStatusInputs.id,
		card_status: customerCardStatusInputs.card_status === 'Released' ? 'released' : 'processing'
	})
	.then(res => {
		// console.log('card status success', res)
		// Set message for success modal
		cardstatusSuccessMessage.value = res.data.message

		// Clean up form state
		customerCardStatusInputs.card_status = null
		customerCardStatusInputs.id = null
		currentCustomerCardStatus.value = ''

		// DOM updates
		editStatusModalVisible.value = false
		cardStatusSuccessModalVisible.value = true
		emit('updatedCustomerCardStatus')
	})
	.catch(err => {
		// console.log('card status err', err)
		if (err.response.status === 500) {
			cardStatusErrorMessage.value = err.response.data
		} else {
			cardStatusErrorMessage.value = err.response.data.message
		}
	})
	.finally(() => {
		submitting.value = false
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
				<p class="text-center">Current Status: {{ currentCustomerCardStatus }}</p>
				<div class="flex justify-center">
					<label 
						for="newStatus" 
						class="font-semibold" 
						style="margin-top: 0.5rem"
					>
						New Status:
					</label>
					<select 
						v-model="customerCardStatusInputs.card_status"
						class="rounded-md mx-2"
						style="border: 1px solid #ccc;"
					>
						<option value="Released">Released</option>
						<option value="On Process">On Process</option>
						<!-- Add other status options as needed -->
					</select>
					<button 
						class="bg-blue-400 border-none rounded-md text-white py-2 px-3"
						@click="updateCardStatus()"
						:disabled="submitting"
					>
						Update Status
					</button>
				</div>
			</div>
		</Modal>
	</div>
</template>