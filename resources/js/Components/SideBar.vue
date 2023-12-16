<script setup>
import { Link } from '@inertiajs/vue3';

const logoutHandler = () => {
	console.log("logout")
}
</script>


<template>
	<nav>
			<div class="link-grp">
				<Link class="link" :href="route('dashboard')">Dashboard</Link>
				<Link class="link" :href="route('customer.index')">User Information Management</Link>
				<Link class="link" :href="route('customer.history')">History</Link>
				<Link class="link" :href="route('customer.backup')">Data Back-up and Recovery</Link>
				<Link class="link" :href="route('customer.announcement')">Create Announcement</Link>
			</div>
			<button @click="logoutHandler">Log out</button>
	</nav>
</template>


<style scoped>
nav {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	padding-right: 3rem;
}
.link-grp {
	display: flex;
	flex-direction: column;
}
.link {
	padding: 10px 20px;
	background-color: #007bff;
	color: white;
	margin: 0.5rem 0;
}
.link:first-of-type {
	margin-top: 0;
}
.link:last-of-type {
	margin-bottom: 0;
}
.link:hover {
	background-color: #fbd24f;
	color: #0a0a0a;
}
</style>