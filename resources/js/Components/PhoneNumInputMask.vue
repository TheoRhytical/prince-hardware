<script setup>
import { vMaska } from "maska"

const props = defineProps({
	modelValue: String,
	inputClass: String,
	required: Boolean,
})
const emit = defineEmits(['update:modelValue'])
</script>

<template>
<div style="margin-bottom: 1.5rem;">
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
	<p style="margin-bottom: 0;">Ex: +63-912-345-6789</p>
</div>
</template>