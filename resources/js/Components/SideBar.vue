<script setup>
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import { watchEffect, ref } from 'vue'
import { router } from '@inertiajs/vue3';

const currentRoute = ref(route().current())
console.log('currentRoute', currentRoute.value)
router.on('navigate', (event) => {
  currentRoute.value = event.detail.page.url
})

const logoutHandler = () => {
	console.log("logout")
}

const links = [
	{
		namedRoute: 'dashboard',
		title: 'Dashboard',
	},
	{
		namedRoute: 'customer.index',
		title: 'User Information Management',
	},
	{
		namedRoute: 'customer.history',
		title: 'History',
	},
	{
		namedRoute: 'customer.backup',
		title: 'Data Back-up and Recovery',
	},
	{
		namedRoute: 'customer.announcement',
		title: 'Create Announcement',
	}
]

const routeCheck = (namedRoute, currentRoute) => {
	console.log("I just need to log this for some reason. Trust me. Don't change this", currentRoute)
	return route().current(namedRoute)
}
</script>


<template>
	<nav>
		<div class="link-grp">
			<NavLink v-for="link in links" :href="route(link.namedRoute)" :isActive="routeCheck(link.namedRoute, currentRoute)">
				{{ link.title }}
			</NavLink>
		</div>
		<button @click="logoutHandler">Log out</button>
	</nav>
</template>


<style scoped>
nav {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	padding-top: 2rem;
	padding-right: 2.5rem;
}
.link-grp {
	display: flex;
	flex-direction: column;
}
</style>