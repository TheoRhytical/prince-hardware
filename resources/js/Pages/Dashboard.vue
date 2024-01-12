<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import '../../css/admin.css';
// import '../../css/bootstrap.min.css';
// import '../../css/all.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
defineOptions({ layout: AuthenticatedLayout })
const props = defineProps({
  adminName: String,
  releasedCards: Number,
  totalCustomers: Number,
  newCustomers: Number,
})
</script>


<template>
  <Head title="Dashboard" />
  <div class="container-fluid bg-white rounded-md p-10">
    <div class="py-10 col-md-10">
      <h3>Welcome, {{ adminName }}!</h3>
    </div>
    <div class="row">
      <div class="col-md-4">
				<div class="card2">
					    <div class="card-body">
					        <div class="backup-container">
					            <span id="backup-icon" class="fas fa-check text-success"></span>
					      		<div id="backup-text">Released: {{  releasedCards }}</div>
					  	</div>
					</div>
				</div>
		  </div>

      <div class="white-space col-md-4">
				<div class="card2">
					    <div class="card-body">
					        <div class="backup-container">
					            <span id="backup-icon" class="fas fa-user text-warning"></span>
					      		<div id="backup-text">Total Users: {{ totalCustomers }}</div>
					  	</div>
					</div>
				</div>
		  </div>

      <div class="white-space col-md-4">
				<div class="card2">
					    <div class="card-body">
					        <div class="backup-container">
					            <span id="backup-icon" class="fas fa-user-plus text-primary"></span>
					      		<div id="backup-text">New Users: {{ newCustomers }}</div>
					  	</div>
					</div>
				</div>
		  </div>
    </div>
    
  </div>

</template>