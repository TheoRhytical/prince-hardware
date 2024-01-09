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
import IndexNav from '@/Components/IndexNav.vue';
import { ref } from 'vue';
import PasswordInput from '@/Components/PasswordInput.vue';
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

const toggleViewPassword = ref(false)
</script>

<template>
  <Head title="Log in" />

  <div>
    <IndexNav />
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
            <PasswordInput 
              :model-value="form.password" 
              @update:model-value="(newValue) => form.password = newValue"
              required
            />
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
  </div>

</template>

<style>
input[type=password]::-ms-reveal,
input[type=password]::-ms-clear
{
    display: none;
}

</style>