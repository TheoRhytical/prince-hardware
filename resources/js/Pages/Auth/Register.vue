<script setup>
import VisitorLayout from '@/Layouts/VisitorLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { reactive, ref } from 'vue';
// import '../../../css/bootstrap.min.css';
// import InputError from '@/Components/InputError.vue';

defineOptions({ layout: VisitorLayout });

const successModal = ref(false)
const successMessage = ref('')

const formInputs = reactive({
  'full_name': null,
  'date_of_birth': null,
  'address': null,
  'email': null,
  'phone_number': '+63-9',
  'password': null,
  'password_confirmation': null,
  'signature': null,
})

const formErrors = reactive({
  'full_name': null,
  'date_of_birth': null,
  'address': null,
  'email': null,
  'phone_number': null,
  'password': null,
  'password_confirmation': null,
  'signature': null,
})


const submitting = ref(false)
const submitRegistration = () => {
  submitting.value = true
  // Uses axios instead of inertiajs form helper because the latter can't submit files
  axios.post('/register', 
    formInputs,
    {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    }
  )
  .then(res => {
    console.log('Success', res)
    successMessage.value = res.data.message
    successModal.value = true
  })
  .catch(err => {
    console.log('Error', err)
    if (err.response.status === 500) {
      // Handle server error
    } else {
      const errors = err.response.data.errors
      for (const field in errors) {
        formErrors[field] = errors[field].join(' ')
      }
      console.log(formErrors)
    }
  })
  .finally(() => {
    submitting.value = false
  })
}

// Preview uploaded signature
import { signatureImgUrl, fileSignatureSelect } from '@/Composables/CustomerForms.js'

// Phone number masking
import PhoneNumInputMask from '@/Components/PhoneNumInputMask.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import InputError from '@/Components/InputError.vue';

</script>


<template>

<Head title="Register Reward Card" />
<div class="main">
  <q-dialog v-model="successModal">
    <q-card>
      <q-card-section>
        <div class="text-h6">Success</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        {{ successMessage }}
      </q-card-section>

      <q-card-actions align="center" style="width: 100%">
		    <Link
			    :href="route('customer.profile')" 
			    as="button"
			    class="btn btn-success w-[10rem] text-white" 
		    >
        OK
        </Link>
      </q-card-actions>
    </q-card>
  </q-dialog>

  <div class="text-white pt-6">
  <h4 class="text-center">PRINCE CARD REGISTRATION FORM</h4>
		<h6 class="text-center">Please provide the following information to complete your registration.</h6>
  </div>

	<div class="form-container">
		<form 
			class="reg-form"
      @submit.prevent="submitRegistration"
      >
			<h4 class="text-center">Personal Information</h4>
		  <label for="fullName">Full Name:</label>
      <InputError v-if="formErrors.full_name" :message="formErrors.full_name" />
		  <input 
        type="text" 
        id="fullName" 
        v-model="formInputs.full_name" 
      >

		  <label for="dob">Date of Birth:</label>
      <InputError v-if="formErrors.date_of_birth" :message="formErrors.date_of_birth" />
		  <input 
        type="date" 
        id="dob" 
        v-model="formInputs.date_of_birth" 
      >

		  <label for="address">Address:</label>
      <InputError v-if="formErrors.address" :message="formErrors.address" />
		  <textarea 
        id="address" 
        v-model="formInputs.address" 
        rows="4" 
      ></textarea>

		  <label for="email">Email:</label>
      <InputError v-if="formErrors.email" :message="formErrors.email" />
		  <input 
        type="email" 
        id="email" 
        v-model="formInputs.email" 
      >

		  <label for="phone">Phone Number:</label>
      <InputError v-if="formErrors.phone_number" :message="formErrors.phone_number" />
      <PhoneNumInputMask 
        @update:model-value="(newValue) => formInputs.phone_number = newValue"
        input-class="reg-input"
      />

      <div class="form-group">
        <label for="password">Password</label>
        <InputError v-if="formErrors.password" :message="formErrors.password" />
        <PasswordInput 
          :model-value="formInputs.password" 
          @update:model-value="(newValue) => formInputs.password = newValue"
          required
        />
      </div>

      <div class="form-group">
        <label for="password">Confirm Password</label>
        <InputError v-if="formErrors.password_confirmation" :message="formErrors.password_confirmation" />
        <PasswordInput 
          :model-value="formInputs.password_confirmation" 
          @update:model-value="(newValue) => formInputs.password_confirmation = newValue"
          required
        />
      </div>

		  <label for="signature">Signature:</label>
      <InputError v-if="formErrors.signature" :message="formErrors.signature" />
		  <input 
        type="file" 
        id="signature" 
        @input="formInputs.signature = $event.target.files[0]"
        accept="image/*" 
        @change="fileSignatureSelect($event, formInputs.signature)"
      >
      <img v-if="signatureImgUrl" :src="signatureImgUrl" />

      <!-- <progress v-if="formInputs.progress" :value="form.progress.percentage" max="100">
      {{ formInputs.progress.percentage }}%
      </progress> -->
			
		  <button type="submit" :disabled="submitting">{{ submitting ? 'Submitting...' : 'Submit' }}</button>
		  <button type="button" class="cancel">Cancel</button>
			
		  <p>A copy of the response will be emailed to the address provided.</p>
    </form>
	</div>

</div>
</template>


<style>
.reg-form > input, 
.reg-input,
.reg-form > textarea
{
  width: 100%;
  padding: 8px;
  margin-bottom: 1.5rem;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* label {
  border-top: 2rem;
} */
</style>


<style scoped>

.form-container{
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Varela Round', sans-serif;
}

.reg-form {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  width: 600px;
  margin-top: 20px;
  margin-bottom: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.50);
}

.reg-form label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
}

.reg-form img {
  max-width: 100%;
  height: auto;
  margin-bottom: 16px;
}

.reg-form button {
  background-color: #4caf50;
  color: #fff;  
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.reg-form button.cancel {
  background-color: red;
  margin-left: 10px;
}

.reg-form p {
  margin-top: 10px;
  font-size: 12px;
  color: #555;
}
</style>