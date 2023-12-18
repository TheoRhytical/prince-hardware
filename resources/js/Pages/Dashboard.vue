<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
defineOptions({ layout: AuthenticatedLayout })
</script>


<template>
  <Head title="Dashboard" />
  <main>
    <h1>You're logged in</h1>
  </main>
</template>