<script setup>
import { ref } from 'vue';
import NavBar from '@/Components/NavBar.vue';
import SideBar from '@/Components/SideBar.vue';
import NavBarOrig from '@/Components/NavBarOrig.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div class="min-h-screen bg-gray-100 window">
    <NavBar />
    <!-- <NavBarOrig /> -->
    <div class="main-window">
      <SideBar />
      <!-- Page Content -->
      <main class="content">
        <slot />
      </main>
    </div>
    <!-- Page Heading -->
    <!-- <header class="bg-white shadow" v-if="$slots.header"> -->
      <!-- <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> -->
        <!-- <slot name="header" /> -->
      <!-- </div> -->
    <!-- </header> -->

  </div>
</template>


<style scoped>
.window {
  height: 100vh;
  display: flex;
  flex-direction: column;
}
.main-window {
  display: grid;
  grid-template-columns: 20rem auto;
  flex: 1;
  padding: 2.5rem 5rem;
}
.content {
  background-color: red;
}

</style>