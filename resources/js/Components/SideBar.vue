<script setup>
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import { links, useCurrentRoute, routeCheck } from '@/Composables/Links.js';

const currentRoute = useCurrentRoute()
console.log('currentRoute', currentRoute.value)

</script>


<template>
	<nav>
		<div class="link-grp">
			<NavLink v-for="link in links" :href="route(link.namedRoute)" :isActive="routeCheck(link.namedRoute, currentRoute)" class="btn btn-primary">
				{{ link.title }}
			</NavLink>
		</div>
		<Link 
			:href="route('logout')" 
			method="post" 
			as="button"
			class="btn btn-danger" 
			id="log-out-btn"
		>
			Log out
		</Link>
	</nav>
</template>


<style scoped>
nav {
	display: flex;
	flex-direction: column;
	/* justify-content: space-between; */
	padding-top: 2rem;
	padding-right: 2.5rem;
	/* min-width: 20%; */
	/* height: 100vh; */
}
.link-grp {
	display: flex;
	flex-direction: column;
}

#log-out-btn {
	margin-top: 10rem;
}
</style>