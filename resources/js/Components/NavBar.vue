<script setup>
import { Link } from '@inertiajs/vue3';
import { useBreakpoints } from '@/Composables/WindowWidth.js';
import NavLink from '@/Components/NavLink.vue';
import { links, useCurrentRoute, routeCheck } from '@/Composables/Links.js';

const currentRoute = useCurrentRoute()
const { width, type } = useBreakpoints()

const logoutHandler = () => {
	console.log("Logout")
}
</script>


<template>
	<nav class="navbar">
		<q-btn color="primary" label="Menu" v-if="type != 'lg'">
      <q-menu>
        <q-list>
          <q-item v-for="link in links" clickable v-close-popup>
						<q-item-section>
							<NavLink :href="route(link.namedRoute)" :isActive="routeCheck(link.namedRoute, currentRoute)">
								{{ link.title }}
							</NavLink>
						</q-item-section>
          </q-item>
        </q-list>
      </q-menu>
    </q-btn>

		<Link href="/dashboard">
  		<img src="/assets/img/logo.png" class="logo-head" id="pulse-grow" title="Logo">
			<!-- test -->
		</Link>
		<button @click="logoutHandler" type="button" class="logout-btn">Log Out</button>
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