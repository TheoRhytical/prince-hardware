<script setup>
import { Link } from '@inertiajs/vue3';

//temporarily unused
const logoutHandler = () => {
	console.log("Logout")
}
</script>


<template>
	<nav class="navbar">
		<Link href="/dashboard">
  			<img src="/assets/img/logo.png" class="logo-head" id="pulse-grow" title="Logo">
				<!-- test -->
		</Link>
		<!-- commented by osmand
		<button @click="logoutHandler" type="button" class="logout-btn btn btn-secondary">Log Out</button>
		-->
		
	</nav>
</template>


<style scoped>
.navbar {
	display: flex;
	justify-content: space-between;
	/* max-width: 100rem; */
}

.logo-head {
	width: 8rem;
	padding: 0.5rem 0;
}
.logo-head {
  transition: transform 0.3s ease-in-out;
}
.logo-head:hover {
  animation: pulse-grow 1s infinite;
}

@keyframes pulse-grow {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}
</style>