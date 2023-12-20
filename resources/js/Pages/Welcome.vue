<script setup>
import { Head, Link } from '@inertiajs/vue3';
import '../../css/mystyle.css';
import '../../css/bootstrap.min.css';
import $ from 'jquery';
import Modal from '@/Components/Modal.vue';

$(document).ready(function (){

	$( ".open-close-btn" ).on('click touchstart', function(e) {
		// prevent default anchor click 
		e.preventDefault();
		$(".overlay").toggleClass("overlay-open");
		$("#hamburger-icon").toggleClass("hamburger-open");
	});

	$(window).on("scroll", function() {
		if($(window).scrollTop() > 50) {
			$(".header").addClass("active");
		} else {
		$(".header").removeClass("active");
		}
	});

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
	
    <div id="col-12" class="header">
		<div id="col-6">
			<a id="hamburger-icon" href="#" class="open-close-btn">
			  <span></span>
			  <span></span>
			  <span></span>
			  <span></span>
			</a>
			<div id="myNav" class="overlay">
				<a href=""><img src="../../icon/home.png" style="" class="nav-head"></a>
				<div class="nav-a-content">
					<Link :href="route('login')" class="nav-link">Login</Link>
					<a href="promo.html" class="nav-link">Promos</a>
			        <a href="loyalty-card.html" class="nav-link">Loyalty Card</a>
			        <a href="about.html" class="nav-link">About Us</a>
					<a href="contact.html" class="nav-link">Contact Us</a>
				</div>
			</div>
		</div>
	</div>

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
	
	
	<div class="container">
    <div class="row">
      <div class="col-12 text-center mt-3 mb-3">
        <button type="button" class="btn btn-success" @click="showModal">CONTINUE TO REGISTRATION</button>
      </div>
    </div>
    <!-- Use the Modal component -->
    <Modal :show="modalVisible" maxWidth="2xl" @close="closeModal">
      <h5 slot="title">Welcome to Prince Card Registration!</h5>
      <h6 class="ml-3 mt-3">Terms and Conditions</h6>
      <div class="modal-body">
        <!-- Your terms and conditions content -->
        By registering for the Prince Card online service, you agree to abide by the following terms and conditions: You must provide accurate and complete information during the registration process. Your Prince Card is for personal use only and may not be transferred or shared. You are responsible for maintaining the confidentiality of your login credentials. Unauthorized use of the Prince Card is strictly prohibited. We may collect and store your personal information as outlined in our privacy policy. The Prince Card is subject to periodic updates and changes. We reserve the right to suspend or terminate your access to the service for violations of these terms.
      </div>
      <label class="ml-3">
        <input type="checkbox" id="agreeCheckbox"> By registering, you acknowledge and accept these terms and conditions.
      </label>
      <div class="modal-footer">
        	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<Link :href="route('register')">
					<button type="button" id="registerBtn" class="btn btn-success">REGISTER HERE</button>
				</Link>
      </div>
    </Modal>
  </div>

</template>
<script>

export default {
  components: {
    Modal, // Register the Modal component
  },
  data() {
    return {
      modalVisible: false, // Control the visibility of the modal
    };
  },
  methods: {
    showModal() {
      this.modalVisible = true; // Logic to show the modal
    },
    closeModal() {
      this.modalVisible = false; // Logic to close the modal
    },
  },
};

</script>


