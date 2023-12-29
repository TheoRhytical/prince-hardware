<script setup>
import { Head, Link } from '@inertiajs/vue3';
import '../../../css/mystyle.css';
import '../../../css/bootstrap.min.css';
import '../../../css/all.min.css';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import $ from 'jquery';


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
	<div id="col-12" class="header" style="">
		<div id="col-6">
			<a id="hamburger-icon" href="#" class="open-close-btn">
			  <span></span>
			  <span></span>
			  <span></span>
			  <span></span>
			</a>
			<div id="myNav" class="overlay">
				<!--dummy link only-->
				<Link :href="route('login')"><img src="../../../icon/home.png" style="" class="nav-head"></Link>
				<div class="nav-a-content">
					<Link href="route('login')" class="nav-link">Login</Link><!---->
					<a href="promo.html" class="nav-link">Promos</a>
					<!-- <h2 class="accordion">Promos</h2>
			            <div class="accordion-panel">
			              <a href=""><p class="panel-ac-p1">1</p></a>
			              <a href=""><p class="panel-ac-p2">2</p></a>
			              <a href=""><p class="panel-ac-p2">3</p></a>
			            </div> -->
			        <a href="loyalty-card.html" class="nav-link">Loyalty Card</a>
			        <a href="about.html" class="nav-link">About Us</a>
					<a href="contact.html" class="nav-link">Contact Us</a>
				</div>
			</div>
		</div>
	</div>

	<div class="body-container">
	<div class="text-container">
		<h2 class="text-center">Welcome, (User Full Name)</h2>
		<h4 class="text-center">Please check below your information and card registration status.</h4>
	</div>
	<div class="user-profile" style="">
		<div class="user-profile-cont" style="">
			<h3 class="text-center" style="padding-top: 20px; padding-bottom: 20px;">Your Personal Information</h3>

		    <label for="fullName">Full Name:</label><br>
		    <p>Juan Dela Cruz</p>
		    
		    <label for="dob">Date of Birth:</label><br>
		   	<p>01/01/2023</p>

		    <label for="address">Address:</label><br>
		   	<p>Cebu, Philippines</p>

		    <label for="email">Email:</label><br>
		    <p>sample@gmail.com</p>
		   
		    <label for="phone">Phone Number:</label>
		    <p>09123456789</p>

		    <label for="signature">Signature:</label><br>
			<div style="display: flex; justify-content: center;">
				<img src="../../../img/sign.jpg" style="width: 40%;" />
			</div>
			
		    
		    <label for="status">Status:</label><br>
		    <span class="btn btn-warning text-white" style="margin-bottom: 50px;">On Process</span><br>

		    <a href="index.html"><button class="btn btn-danger" style="margin-bottom: 20px;">Logout</button></a>
		</div>	
	</div>
 </div>
</template>