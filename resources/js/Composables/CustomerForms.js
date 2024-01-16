import { reactive, ref } from "vue";

// State and functions related to customer edit are stored here to 
// * let the modals be a component 
// * connect the modals to the edit button in the main table

// Handles signature image preview
export const signatureImgUrl = ref('')
export const fileSignatureSelect = (event, signature) => {
  const file = event.target.files[0];
  signatureImgUrl.value = URL.createObjectURL(file);
  // signature = file
}

export const getModifiedInput = (oldData, newData) => {
  const keys1 = Object.keys(oldData)
  const keys2 = Object.keys(newData)
  if (JSON.stringify(keys1) !== JSON.stringify(keys2)) throw new Error('Keys are not similar');
  let modifiedData = {}
  keys1.forEach(key => {
    if (oldData[key] !== newData[key]) modifiedData[key] = newData[key]
  })
	if (Object.keys(modifiedData).length === 0) throw new Error('No changes to current data');
  modifiedData.id = oldData.id
  return modifiedData
}

// Edit customer info
/**
 * NOTE: 
 * currentCustomer 					holds state
 * customerData 						holds v-model bindings for new data in modal form
 * unchangedCustomerdata 		holds unchanged data to be used for comparison later
 */
export const currentCustomer = ref(false)
export let unchangedCustomerData = {
	'id': null,
  'full_name': null,
  'date_of_birth': null,
  'address': null,
  'email': null,
  'phone_number': null,
  'signature': null,
}
export let customerData = reactive(structuredClone(unchangedCustomerData))
export const editModalVisible = ref(false)
export const signatureImgurl = ref(null)
export const editErrorMessage = ref('')
export const openEditInfoModal = (row) => {
  // Reference to current row is needed in updating image shown in table
	currentCustomer.value = row
	unchangedCustomerData = {
		'id': row.id,
  	'full_name': row.full_name,
  	'date_of_birth': row.date_of_birth,
  	'address': row.address,
  	'email': row.user.email,
  	'phone_number': row.phone_number,
  	'signature': null,
	}
  for (const key in customerData) {
    customerData[key] = unchangedCustomerData[key]
  }
	signatureImgUrl.value = row.signature_filename
	editErrorMessage.value = ''
	// console.log("Edit", row, row.id)
	editModalVisible.value = true
}