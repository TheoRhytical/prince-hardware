<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import '../../../css/mystyle.css';
import '../../../css/bootstrap.min.css';
import '../../../css/all.min.css';
import $ from 'jquery';

const form = useForm({
    name: '',
    contact: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />
   <div id="col-12" class="header">
		<div id="col-6">
			<a id="hamburger-icon" href="#" class="open-close-btn">
			  <span></span>
			  <span></span>
			  <span></span>
			  <span></span>
			</a>
			<div id="myNav" class="overlay">
				<a href="index.html"><img src="../../../icon/home.png" style="" class="nav-head"></a>
				<div class="nav-a-content">
					<a href="login.html" class="nav-link">Login</a><!---->
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

	<div class="text-container">
		<h2 class="text-center">PRINCE CARD REGISTRATION FORM</h2>
		<h4 class="text-center">Please provide the following information to complete your registration.</h4>
	</div>
	<div class="form-container">	
		<form class="reg-form" id="registrationForm" action="#" method="post" enctype="multipart/form-data">
			<h3 class="text-center">Personal Information</h3>

		    <label for="fullName">Full Name:</label>
		    <input type="text" id="fullName" name="fullName" required>

		    <label for="dob">Date of Birth:</label>
		    <input type="date" id="dob" name="dob" required>

		    <label for="address">Address:</label>
		    <textarea id="address" name="address" rows="4" required></textarea>

		    <label for="email">Email:</label>
		    <input type="email" id="email" name="email" placeholder="(Optional)">

		    <label for="phone">Phone Number:</label>
		    <input type="tel" id="phone" name="phone" required>

		  	<label for="password">Password:</label>
			<div class="password-container">
			    <input type="password" id="password" name="password" required>
		
			</div>

			<label for="confirmpass">Confirm Password:</label>
			<div class="password-container">
			    <input type="password" id="confirmpass" name="confirmpass" required>
			    <i class="toggle-password fas fa-eye" onclick="togglePasswordVisibility()"></i>
			</div>

		    <label for="signature">Signature:</label>
		    <input type="file" id="signature" name="signature" accept="image/*" required>
		    <img id="signaturePreview" alt="Signature Preview">

		    <button type="submit" onclick="showConfirmation()">Submit</button>
		    <button type="button" class="cancel">Cancel</button>

		    <p>A copy of the response will be emailed to the address provided.</p>
		</form>
	</div>
	  <!-- Modal Overlay -->
   <div id="confirmationModal" class="alert-overlay">
    <div class="alert-content">
      <p>Congratulations! Your Prince Card registration is complete.</p>
      <button class="btn btn-success" onclick="closeModal()">Close</button>
    </div>
  </div>
</template>
