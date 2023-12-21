<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import '../../../css/admin.css';
import '../../../css/bootstrap.min.css';
import '../../../css/all.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

defineOptions({ layout: AuthenticatedLayout })
</script>


<template>
	<Head title="Backup" />
		<div id="col-12">
			<div class="white-space">
				<div class="card">
					    <div class="card-body">
					        <div class="backup-container">
					            <span id="backup-icon" class="fas fa-database"></span>
					      		<div id="backup-text">Back-up</div>
					  	</div>
					</div>
				</div>
			</div>
		</div>
</template>