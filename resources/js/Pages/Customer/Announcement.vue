<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import '../../../css/admin.css';
defineOptions({ layout: AuthenticatedLayout })
</script>


<template>
	<Head title="Announcement" />

	<div id="col-12" class="main-content">
			<div id="col-12">
				<div class="white-space">
					<h1 style="font-family: 'Varela Round', sans-serif;text-align: center;font-size: 27px;text-transform: uppercase;margin-top: 58px;">Send an announcement to our valid users!</h1>
					<div class="compose-form">
						<form id="composeEmailForm">

							<label for="subject">Subject:</label>
							<input type="text" id="subject" name="subject" required>

							<label for="message">Message:</label>
							<textarea id="message" name="message" rows="4" required></textarea>

							<input type="submit" value="Send Email">
						</form>
					</div>
				</div>
			</div>
		</div>
</template>