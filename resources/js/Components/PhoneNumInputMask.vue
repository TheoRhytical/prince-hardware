<script setup>
import { vMaska } from "maska"

const props = defineProps({
	'modelValue': {
		type: String
	},
	'inputClass': {
		type: String
	},
	'required': {
		type: Boolean
	}
})
const emit = defineEmits(['update:modelValue'])
</script>

<template>
<div>
	<input
		:required="required"
		type="tel"
		:class="[inputClass]"

    :value="props.modelValue"
    @input="emit('update:modelValue', $event.target.value)"

		v-maska
		data-maska="+63-9##-###-####"
		placeholder="+63-9__-___-____"
		style="margin-bottom: 0;"
	/>
	<p>Ex: +63-912-345-6789</p>
</div>
</template>