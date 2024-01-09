<script setup>
import { Head, Link } from '@inertiajs/vue3';
import '../../css/mystyle.css';
import '../../css/bootstrap.min.css';
// import '#/scss/styles.scss'
// import * as bootstrap from 'bootstrap'
import $ from 'jquery';
import Modal from '@/Components/Modal.vue';
import IndexNav from '@/Components/IndexNav.vue';
import { ref } from 'vue';

// defineOptions({ layout: AuthenticatedLayout });
const props = defineProps({
	'canLogin': {
		type: Boolean,
		required: true,
	},
	'canRegister': {
		type: Boolean,
		required: true,
	},
	'userType': {
		type: String,
	},
});


const modalVisible = ref(false)
const showModal = () => {
	modalVisible.value  = true; // Logic to show the modal
};
const closeModal = () => {
	modalVisible.value = false; // Logic to close the modal
};

const acceptTnC = ref(false)
$(document).ready(function (){
	document.addEventListener('DOMContentLoaded', function () {
    const agreeCheckbox = document.getElementById('agreeCheckbox');
    const registerBtn = document.getElementById('registerBtn');

    registerBtn.disabled = true;

    agreeCheckbox.addEventListener('change', function () {
    	registerBtn.disabled = !agreeCheckbox.checked;
    });
	});
});
</script>

<template>
	<Head title="Welcome" />
	<IndexNav :authenticated="!canLogin" :user-type="userType"/>
	
	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
	    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
	    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
	    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
	  </ol>
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <img class="d-block w-100" src="../../img/banner-1.jpg" alt="First slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="../../img/banner-1.jpg" alt="Second slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="../../img/banner-1.jpg" alt="Third slide">
		    </div>
		  </div>
		  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
	</div>
	
	
	<div v-if="canRegister" class="container">
    <div class="row">
      <div class="col-12 text-center mt-3 mb-3">
        <button type="button" class="btn btn-success" @click="showModal">CONTINUE TO REGISTRATION</button>
      </div>
    </div>
    <!-- Use the Modal component -->
    <Modal 
			:show="modalVisible" 
			max-width='md' 
			@close="closeModal"
			modal-class="modal-tnc"
		>
      <h5 slot="title" id="title">Welcome to Prince Card Registration!</h5>
			<hr />
      <h6 id="subtitle">Terms and Conditions</h6>
      <div class="modal-body">
        <!-- Your terms and conditions content -->
        By registering for the Prince Card online service, you agree to abide by the following terms and conditions: You must provide accurate and complete information during the registration process. Your Prince Card is for personal use only and may not be transferred or shared. You are responsible for maintaining the confidentiality of your login credentials. Unauthorized use of the Prince Card is strictly prohibited. We may collect and store your personal information as outlined in our privacy policy. The Prince Card is subject to periodic updates and changes. We reserve the right to suspend or terminate your access to the service for violations of these terms.
      </div>
      <label class="mt-3">
        <input 
					type="checkbox" 
					id="agreeCheckbox"
					v-model="acceptTnC"
				>
				By registering, you acknowledge and accept these terms and conditions.
      </label>
      <div class="modal-footer">
        	<button type="button" class="btn btn-danger" data-dismiss="modal" @click="closeModal">Close</button>
				<Link 
					:href="route('register')"
					as="button"
					id="registerBtn" 
					class="btn btn-success" 
					:disabled="!acceptTnC"
				>
					REGISTER HERE
				</Link>
      </div>
    </Modal>
  </div>

</template>


<style>
.modal-tnc {
	padding: 1rem;
}

#title {
	margin-top: 0.5rem;
	margin-bottom: 1rem;
}

#subtitle {
	margin-top: 1rem;
	margin-bottom: 1.5rem;
}
</style>