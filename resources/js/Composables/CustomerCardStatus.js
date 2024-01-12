import { reactive, ref } from 'vue'

export const editStatusModalVisible = ref(false)

// * currentCustomerCardStatus holds value of old data for later comparison
// * customerCardStatusInputs holds form state for submission
export const currentCustomerCardStatus = ref('')
export const customerCardStatusInputs = reactive({
	id: null,
	card_status: null
})
export const cardStatusErrorMessage = ref('')

export const openEditStatusBtn = (row) => {
	currentCustomerCardStatus.value = row.card_status
	customerCardStatusInputs.card_status = row.card_status
	customerCardStatusInputs.id = row.id
	cardStatusErrorMessage.value = ''
	editStatusModalVisible.value = true
}