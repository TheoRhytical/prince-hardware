<script setup>
import IndexNav from '@/Components/IndexNav.vue';


</script>

<template>
<main class="min-h-screen">
	<IndexNav />
	<slot />
</main>
</template>

<style scoped>
main {
  background: linear-gradient(45deg, #eeb405 10%, #dc3545 100%);
  font-size: 16px;
  font-family: Tahoma;
}
</style>