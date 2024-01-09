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
<div class="input-group">
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
      class="input-group-text" 
      style="cursor: pointer;" 
      @click="toggleViewPassword = !toggleViewPassword"
    >
    <i class="fas" :class="[iconClass]"></i>
    </span>
  </div>
</div>
</template>