import { ref, reactive } from 'vue';

export const updateModalVisible = ref(false)

// currentData holds old data for later comparison
export let currentData = {
	id: null,
	full_name: null,
	registered_date: null,
	released_date: null,
}
// newData holds form state
export const newData = reactive({
	id: null,
	full_name: null,
	registered_date: null,
	released_date: null,
})

export const updateErrorMessage = ref('')

export const openUpdateHistoryModal = (row) => {
	console.log('history', row)
	currentData = {
		id: row.id,
		full_name: row.full_name,
		registered_date: row.registered_date_ymd,
		released_date: row.released_date_ymd,
	}
	for (const key in currentData) {
		newData[key] = currentData[key]
	}
	updateErrorMessage.value = ''
	updateModalVisible.value = true
}