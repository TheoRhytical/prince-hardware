<script setup>
import { ref, watchEffect } from 'vue';
import Modal from './Modal.vue';

const props = defineProps({
	toDeleteId: {
		// type: Number,
		required: true,
	},
})

const emits = defineEmits(['deleted'])

const deleteModalVisible = ref(false)
const deleteCustomerSuccessMessage = ref('')
const deleteSuccessModalVisible = ref(false)
const submitting = ref(false)

watchEffect(() => {
	if (props.toDeleteId !== null) deleteModalVisible.value = true
})

const deleteCustomer = () => {
	submitting.value = true
	axios.delete(route('customer.delete'), {
		data: {
			id: toDeleteId
		}
	})
	.then(res => {
		console.log('success', res)
		deleteCustomerSuccessMessage.value = res.data.message
		deleteSuccessModalVisible.value = true
	})
	.catch(err => {
		console.log('error', err)
	})
	.finally(() => {
		submitting.value = false
		deleteModalVisible.value = false
	})
}
</script>

<template>
  <Modal
		:show="deleteModalVisible" 
		max-width='md' 
		@close="deleteModalVisible = false"
		modal-class="edit-modal"
	>
		<div class="modal-content" style="max-width: none;">
			<p class="text-center">Are you sure you want to delete this record?</p>
			<div class="flex justify-center">
				<div style="width: 50%" class="flex justify-evenly">
					<button 
						:disabled="submitting"
						@click="deleteCustomer"
						class="bg-blue-400"
						>
						YES
					</button>
					<button 
						@click="deleteModalVisible = false"
						class="bg-red-500"
						>
						NO
					</button>
				</div>
			</div>
		</div>
	</Modal>

  <Modal
		:show="deleteSuccessModalVisible" 
		max-width='md' 
		@close="deleteSuccessModalVisible = false"
		modal-class="edit-modal"
	>
		<div class="modal-content" style="max-width: none;">
			{{ deleteCustomerSuccessMessage}}
		</div>
	</Modal>
</template>

<style scoped>
button {
	@apply py-2 px-3;
}
</style>