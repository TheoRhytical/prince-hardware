<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import '../../../css/admin.css';
import '../../../css/bootstrap.min.css';
import '../../../css/all.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { ref } from 'vue';
import axios from 'axios';

defineOptions({ layout: AuthenticatedLayout })

const email = ref('')
const loading = ref(false)
const responseMsg = ref('')
const responseStatusClass = ref('')
const onDownload = () => {
	loading.value = true
	axios.post('/api/customer/backup', {
		email: email.value
	})
	.then(res => {
		responseStatusClass.value = 'alert-success'
		responseMsg.value = res.data.message
		loading.value = false
	})
	.catch(err => {
		responseStatusClass.value = 'alert-danger'
		responseMsg.value = err.response.data.message
		loading.value = false
	})
}
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
			<div class="form-position">
				<div class="form-container bg-gray-100">
					<form class="search-group flex ">
						<input v-model="email" class="me-2 grow" placeholder="Email" aria-label="Email">
						<button @click.prevent="onDownload" class="btn btn-outline-success" type="submit" :disabled="loading">
							<!-- Add loading spinner if possible -->
							<div v-if="loading" class="spinner-border " role="status">
  							<span class="visually-hidden">Downloading...</span>
							</div>
							<span v-else>
								Download Backup
							</span>
						</button>
					</form>
					<p style="margin-bottom: 0;">Download a CSV file of customers' data to provided email</p>
					<div v-if="responseMsg">
						<div class="alert" :class="[responseStatusClass]">{{ responseMsg }}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>


<style scoped>
.form-position {
	display: flex;
	justify-content: center;
}
.form-container {
	/* display: flex; */
	/* justify-content: center; */
	width: 30rem;
	margin-top: 2rem;
	padding: 1rem 2rem;
	border-radius: 0.25rem;
  box-shadow: 0 0 2rem 0.25rem rgba(0,0,0,0.2);
}
.search-group {
	display: flex;
	/* justify-content: center; */
}
</style>