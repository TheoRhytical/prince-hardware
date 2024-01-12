<script setup>
import { Head, Link } from '@inertiajs/vue3';
import '../../../css/mystyle.css';
import '../../../css/bootstrap.min.css';
import '../../../css/all.min.css';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import $ from 'jquery';
import IndexNav from '@/Components/IndexNav.vue';

const props = defineProps({
	full_name: {
		type: String,
		required: true,
	},
	date_of_birth: {
		type: String,
		required: true,
	},
	address: {
		type: String,
		required: true,
	},
	email: {
		type: String,
		required: true,
	},
	phone_number: {
		type: String,
		required: true,
	},
	signature_src: {
		type: String,
		required: true,
	},
	card_status: {
		type: String,
		required: true,
	}
});

$(document).ready(function(){
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
});
</script>

<template>
<Head title="User Dashboard" />
<div class="body-container min-h-screen">
	<IndexNav authenticated />
	<div class="text-container">
		<h2 class="text-center">Welcome, {{ full_name }}</h2>
		<h4 class="text-center">Please check below your information and card registration status.</h4>
	</div>
	<div class="user-profile" style="">
		<div class="user-profile-cont" style="">
			<h3 class="text-center" style="padding-top: 20px; padding-bottom: 20px;">Your Personal Information</h3>

		  <label for="fullName">Full Name:</label><br>
		  <p>{{ full_name }}</p>

		  <label for="dob">Date of Birth:</label><br>
		  <p>{{ date_of_birth }}</p>

		  <label for="address">Address:</label><br>
		  <p>{{ address }}</p>

		  <label for="email">Email:</label><br>
		  <p>{{ email }}</p>

		  <label for="phone">Phone Number:</label>
		  <p>{{ phone_number }}</p>

		  <label for="signature">Signature:</label><br>
			<div style="display: flex; justify-content: center;">
				<img :src="route('customer.signature')" style="width: 40%;" />
			</div>
			
		    
		  <label for="status">Status:</label><br>
		  <span 
				class="btn btn-warning text-white"
				style="margin-bottom: 50px;"
			>
			{{ card_status }}
			</span><br>

			<Link 
				:href="route('logout')" 
				method="post" 
				as="button"
				class="btn btn-danger" 
				style="margin-bottom: 20px;"
			>
				Log out
			</Link>
		</div>	
	</div>
</div>
</template>