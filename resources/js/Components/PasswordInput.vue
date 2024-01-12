<script setup>
import { ref, computed  } from 'vue';
import '@fortawesome/fontawesome-free/css/all.min.css';

const props = defineProps({
	modelValue: String, 
	required: Boolean
})
const emit = defineEmits(['update:modelValue'])
const toggleViewPassword = ref(false)

const iconClass = computed(() => toggleViewPassword.value ? 'fa-eye-slash' : 'fa-eye')
</script>


<template>
<div class="input-group password-group">
  <input
    :type="toggleViewPassword ? 'text' : 'password'" 

		:value="props.modelValue"
    @input="emit('update:modelValue', $event.target.value)"

		:required="required"
    class="form-control" 
    id="password" 
  />
  <div class="input-group-append">
    <span 
      class="input-group-text eye" 
      style="cursor: pointer;" 
      @click="toggleViewPassword = !toggleViewPassword"
    >
    <i class="fas" :class="[iconClass]"></i>
    </span>
  </div>
</div>
</template>

<style scoped>
.eye {
  border-radius: 0 var(--bs-border-radius) var(--bs-border-radius) 0;
  height: 100%;
}
input[type=password]::-ms-reveal,
input[type=password]::-ms-clear
{
    display: none !important;
}
.password-group {
  margin-bottom: 1.5rem;
}
</style>