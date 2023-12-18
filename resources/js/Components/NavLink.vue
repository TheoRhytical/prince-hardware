<script setup>
import { computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
	href: {
		type: String,
		required: true,
	},
	isActive: {
		type: Boolean,
	}
})

</script>

<template>
	<Link :href="href"
		class="link"
		:class="{active: isActive}"
		>
		<slot />
	</Link>
</template>

<style scoped>
.link {
	padding: 10px 20px;
	background-color: #007bff;
	color: white;
	margin: 0.5rem 0;
	transition: 0.15s;
}
.link:first-of-type {
	margin-top: 0;
}
.link:last-of-type {
	margin-bottom: 0;
}
.link:hover, .link.active {
	background-color: #fbd24f;
	color: #0a0a0a;
}
.link:hover {
	transition: 0.1s;
}
</style>