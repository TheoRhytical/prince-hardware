<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import '@fortawesome/fontawesome-free/css/all.min.css';
import TextInput from '@/Components/TextInput.vue';
import NavLayout from '@/Layouts/NavLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import '../../../css/mystyle.css';
import '../../../css/bootstrap.min.css';
import '../../../css/all.min.css';
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
defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

        <div id="col-12" class="header" style="">
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
                        <Link :href="route('login')" class="nav-link">Login</Link>
                        <a href="promo.html" class="nav-link">Promos</a>
                        <a href="loyalty-card.html" class="nav-link">Loyalty Card</a>
                        <a href="about.html" class="nav-link">About Us</a>
                        <a href="contact.html" class="nav-link">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div class="login-container">
                <h1 class="text-center" style="color: white;">Welcome to Online Prince Card Registration</h1>
                <div class="login-form">
                    <h2 class="text-center" style="color: white;">Login</h2>
                    
                    <div class="form-group">
                        <label for="contact">Phone Number or Email</label>

                        <TextInput 
                            id="contact" 
                            type="text" 
                            class="form-control" 
                            v-model="form.email" 
                            required 
                            autofocus
                            autocomplete="email"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                        <TextInput type="password" class="form-control" id="password" v-model="form.password" required />
                        <InputError class="mt-2" :message="form.errors.password" />
                        <div class="input-group-append">
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        </div>
                    </div>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                >
                    Forgot your password?
                </Link>
                <div class="block mt-4">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    
                </div>
            </div> 
        </form>
</template>


