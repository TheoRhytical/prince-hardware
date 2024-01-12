<script setup>
// Preview uploaded signature
import { 
	currentCustomer,
	customerData,
	editErrorMessage,
	editModalVisible,
	fileSignatureSelect,
	getModifiedInput,
	signatureImgUrl,
	unchangedCustomerData
} from '@/Composables/CustomerForms.js';
import { } from '@/Composables/CustomerForms.js';
import PhoneNumInputMask from '@/Components/PhoneNumInputMask.vue';
import { ref } from 'vue';
import Modal from './Modal.vue';
import axios from 'axios';

const emit = defineEmits(['closeModal', 'updatedCustomer'])

const editSubmitting = ref(false)

const editCustomerSuccessMessage = ref('')
const editSuccessModalVisible = ref(false)

const editCustomer = () => {
	editSubmitting.value = true
	let modifiedInput
	// console.log('customerData', unchangedCustomerData, customerData)
	try {
		modifiedInput = getModifiedInput(unchangedCustomerData, customerData)
	} catch (error) {
		editErrorMessage.value = error.message
		editSubmitting.value = false
		return
	}
	// console.log('new data', modifiedInput)
	axios.post(route('customer.edit'), modifiedInput,
		{
			...(modifiedInput?.signature && 
			{ headers: {'Content-Type': 'multipart/form-data'} }
			)
		},
	)
	.then(res => {
		// console.log('Success', res)
		editCustomerSuccessMessage.value = res.data.message
		editSuccessModalVisible.value = true
		editModalVisible.value = false
		// emit('closeModal')
		if ('signature' in modifiedInput) {
			currentCustomer.value.signature.src = res.data.customer.signature_filename
		}
		emit('updatedCustomer')
		// tableRef.value.requestServerInteraction();
	})
	.catch(err => {
		// console.log('Error', err)
		editErrorMessage.value = err.response.data.message
	})
	.finally(() => {
		editSubmitting.value = false
	})
}
</script>

<template>
	<div>
  	<Modal
			:show="editSuccessModalVisible" 
			max-width='md' 
			@close="editSuccessModalVisible = false"
			modal-class="edit-modal"
		>
			<div class="modal-content" style="max-width: none;">
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
				<form 
					id="updateRecordForm"
					@submit.prevent="editCustomer"
				>
					<div v-if="editErrorMessage" class="alert alert-danger" role="alert">
						{{ editErrorMessage }}
					</div>

					<!-- <h4>Update Record</h4> -->
					<div class="input-group">
						<label for="updateName">Full Name:</label>
						<input 
							v-model="customerData.full_name"
							type="text" 
							id="updateName" 
						/>
					</div>

					<div class="input-group">
						<label for="dateOfBirth">Date of Birth:</label>
						<input 
							v-model="customerData.date_of_birth"
							type="date" 
							id="dateOfBirth" 
						/>
					</div>

					<div class="input-group">
						<label for="address">Address:</label>
						<input 
							v-model="customerData.address"
							type="text" 
							id="address" 
						/>
					</div>

					<div class="input-group">
						<label for="email">Email:</label>
						<input 
							v-model="customerData.email"
							type="email" 
							id="email" 
						/>
					</div>

					<div class="input-group">
						<label for="phone">Phone Number:</label>
      			<PhoneNumInputMask
							:model-value="customerData.phone_number"
        			@update:model-value="(newValue) => customerData.phone_number = newValue"
							class="phone-input"
      			/>
					</div>

					<div id="signature-input-group">
						<label for="signature">Signature (image):</label>
						<input 
							@input="customerData.signature = $event.target.files[0]"
							type="file" 
							id="signature" 
							accept="image/*" 
							@change="fileSignatureSelect($event, customerData.signature)"
						/>
						<div
							v-if="signatureImgUrl"
							class="flex justify-center"
						>
							<img 
								:src="signatureImgUrl" 
								style="max-height: 10rem;"
							/>
						</div>
					</div>

					<div class="flex justify-center mt-3">
						<button 
							type="submit" 
							:disabled="editSubmitting"
							class="bg-blue-400 border-none rounded-md text-white py-1 px-2"
							>
							{{ editSubmitting ? 'Updating... ' : 'Update' }}
						</button>
					</div>
				</form>
			</div>
		</Modal>
	</div>
</template>

<style lang="scss">
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
		@include label-style;
	}

	input {
		@include input-style;
	}
}

.phone-input {
	margin-bottom: 0;
	display: flex;
	flex-direction: column;

	input {
		width: 100%;
		// @include form-input;
	}
}

#signature-input-group {
	input {
		@include input-style;
		width: 100%;
	}
	label {
		@include label-style;
	}
}
</style>