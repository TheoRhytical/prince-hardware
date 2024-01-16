<script setup>
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import {
	currentData,
	newData,
	updateErrorMessage,
	updateModalVisible
} from '@/Composables/CustomerHistory.js';
import { getModifiedInput } from '@/Composables/CustomerForms';
import axios from 'axios';

const emit = defineEmits(['updatedCustomerHistory'])

const updateSuccesModalVisible = ref(false)
const updateSuccessMessage = ref('')
const submitting = ref(false)

const updateCustomerHistory = () => {
	submitting.value = true
	let modifiedInput
	try {
		modifiedInput = getModifiedInput(currentData, newData)
	} catch (error) {
		updateErrorMessage.value = error.message
		submitting.value = false
		return
	}
	// Pass registered date for backend processing if any date is present
	if ('released_date' in modifiedInput || 'registered_date' in modifiedInput)
		modifiedInput.registered_date = newData.registered_date
	axios.patch(route('customer.history.edit'), modifiedInput)
	.catch(err => {
		if (err.response.status === 500) {
			updateErrorMessage.value = err.response.data
		} else {
			updateErrorMessage.value = err.response.data.message
		}
	})
	.then(res => {
		// Clean up state
		for (const key in newData) {
			currentData[key] = null
			newData[key] = null
		}

		// DOM updates
		updateSuccessMessage.value = res.data.message
		updateModalVisible.value = false
		updateSuccesModalVisible.value = true
		emit('updatedCustomerHistory')
	})
	.finally(() => {
		submitting.value = false
	})
}
</script>

<template>
	<div>
  	<Modal
			:show="updateSuccesModalVisible" 
			max-width='md' 
			@close="updateSuccesModalVisible= false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
				{{ updateSuccessMessage }}
			</div>
		</Modal>

  	<Modal
			:show="updateModalVisible" 
			max-width='md' 
			@close="updateModalVisible = false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
				<form @submit.prevent="updateCustomerHistory">
					<div v-if="updateErrorMessage" class="alert alert-danger" role="alert">
						<pre>
							{{ updateErrorMessage }}
						</pre>
					</div>

					<!-- <h4>Update Record</h4> -->
					<div class="input-group">
						<label for="updateName">Full Name:</label>
						<input 
							type="text" 
							id="updateName"
							v-model="newData.full_name"
						/>
					</div>

					<div class="input-group">
						<label for="dateregistered">Registered Date:</label>
						<input
							type="date" 
							id="dateregistered"
							v-model="newData.registered_date"
						/>
					</div>

					<div class="input-group">
						<label for="datereleased">Released Date:</label>
						<input 
							type="date" 
							id="release"
							v-model="newData.released_date"
						/>
					</div>

					<div class="flex justify-center mt-3">
						<button 
							type="submit" 
							:disabled="submitting"
							class="bg-blue-400 py-1 px-2"
						>
							Update
						</button>
					</div>
				</form>
			</div>
		</Modal>
	</div>
</template>

<style lang="scss" scoped>
@mixin input-style {
		border: 1px solid #ccc;
		@apply rounded-md;
		padding: 0.5rem 1rem;
}
@mixin label-style {
		text-align: center;
		font-weight: 600;
}
.input-group {
	display: flex;
	flex-direction: column;
	margin-bottom: 1rem;

	> label {
		@include label-style
	}

	> input {
		@include input-style
	}
}
</style>