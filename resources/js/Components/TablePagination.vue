<script setup>
import { computed } from 'vue';
const props = defineProps({
	currentPage: {
		required: true,
		type: Number,
	},
	lastPage: {
		required: true,
		type: Number,
	}
})
const emit = defineEmits(['navigatePage'])
const pageArrLen = 15;	// change this as needed; must be odd number
const createArr = (m, n) => {
	return Array.from({length: n - m + 1}, (_, i) => m + i)
}
const pages = computed(() => {
	if (props.currentPage < pageArrLen) {
		return createArr(1, pageArrLen).concat(['...', props.lastPage]);
	} else if (props.currentPage > props.lastPage - pageArrLen + 1) {
		let m = props.lastPage - pageArrLen + 1;
		return [1, '...'].concat(createArr(m, props.lastPage));
	} else {
		let diff = Math.floor((pageArrLen - 2) / 2)
		let m = props.currentPage - diff
		let n = props.currentPage + diff
		return [1, '...'].concat(createArr(m, n)).concat(['...', props.lastPage]);
	}
})

// 1 2 3 4 5 .. 500
// 1 .. 4 5 6 .. 500
// 1 .. 496 497 498 499 500
const pageNavigationHandler = (page) => {
	emit('navigatePage', page)
}

</script>

<template>
<nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item">
      <button @click="pageNavigationHandler(currentPage - 1)" class="page-link" :class="{disabled: currentPage == 1}" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </button>
    </li>
    <li v-for="page in pages" class="page-item" :class="{ active: page == currentPage }">
			<button v-if="page != '...'" @click="pageNavigationHandler(page)" class="page-link">{{ page }}</button>
			<span v-else class="page-link" disabled>{{ page }}</span>
		</li>
    <li class="page-item">
      <button @click="pageNavigationHandler(currentPage + 1)" class="page-link" :class="{disabled: currentPage == lastPage}"  aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </button>
    </li>
  </ul>
</nav>
</template>

<style scoped>
	.page-link[disabled] {
		pointer-events: none;
	}
</style>