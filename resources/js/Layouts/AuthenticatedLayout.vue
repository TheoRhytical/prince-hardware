<script setup>
import NavBar from '@/Components/NavBar.vue';
import SideBar from '@/Components/SideBar.vue';
import NavBarOrig from '@/Components/NavBarOrig.vue';
import { ref, watchEffect } from 'vue';
import { useBreakpoints } from '@/Composables/WindowWidth.js';

const { width, type } = useBreakpoints()
// watchEffect(() => {
//   console.log(width.value , type.value )
// })
</script>

<template>
  <div class="min-h-screen bg-gray-100 window">
    <NavBar />
    <!-- <NavBarOrig /> -->
    <div :class="{'main-window': type === 'lg'}">
      <!-- Show  -->
      <SideBar v-if="type === 'lg'"/>

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
  /* height: 100vh; */
  display: flex;
  flex-direction: column;
  padding: 0 2.5rem;
  background: linear-gradient(45deg, #eeb405 10%, #dc3545 100%);
}
.main-window {
  display: grid;
  grid-template-columns: 20rem auto;
  flex: 1;
  padding: 0 0 2.5rem;
}
.content {
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 0 2rem 0.25rem rgba(0,0,0,0.2);
}

</style>