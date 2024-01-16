export const links = [
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


import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

export const useCurrentRoute = () => {
	const currentRoute = ref(route().current())
	router.on('navigate', (event) => {
		currentRoute.value = event.detail.page.url
	})
	return currentRoute
}


export const routeCheck = (namedRoute, currentRoute) => {
	// console.log("I just need to log this for some reason. Trust me. Don't change this", currentRoute)
	return route().current(namedRoute)
}