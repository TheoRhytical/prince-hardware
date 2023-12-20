<script setup>
import { Head, Link } from '@inertiajs/vue3'
import '../../css/mystyle.css';
import '../../css/bootstrap.min.css';
</script>

<template>
    		<div id="col-6">
    			<a id="hamburger-icon" href="#" class="open-close-btn">
    			  <span></span>
    			  <span></span>
    			  <span></span>
    			  <span></span>
    			</a>
    			<div id="myNav" class="overlay">
    				<a href="index.html"><img src="../../img/home.png" style="" class="nav-head"></a>
    				<div class="nav-a-content">
    					<a href="login.html" class="nav-link">Login</a><!---->
    					<a href="promo.html" class="nav-link">Promos</a>
    			        <a href="loyalty-card.html" class="nav-link">Loyalty Card</a>
    			        <a href="about.html" class="nav-link">About Us</a>
    					<a href="contact.html" class="nav-link">Contact Us</a>
    				</div>
    			</div>
    		</div>
</template>