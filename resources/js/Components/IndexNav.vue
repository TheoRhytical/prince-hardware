<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  authenticated: {
    type: Boolean,
    default: false
  },
  userType: {
    type: String,
  }
})

const hamburgerOpen = ref(false);
const openHamburger = () => {
	hamburgerOpen.value = !hamburgerOpen.value
  console.log('open/close')
}
</script>

<template>
  <main>
	  <a 
      id="hamburger-icon" 
      href="#" 
      class="open-close-btn" 
      :class="{'hamburger-open': hamburgerOpen}"
      @click="openHamburger"
    >
		  <span></span>
		  <span></span>
		  <span></span>
		  <span></span>
	  </a>

    <div 
      id="myNav" 
      class="overlay"
      :class="{'overlay-open': hamburgerOpen}"
    >
      <Link href="/">
        <img src="/assets/icon/home.png" class="nav-head" />
      </Link>
      <div class="nav-a-content">
        <Link v-if="authenticated && userType === 'customer'" :href="route('customer.profile')" class="nav-link">Profile</Link>
        <Link v-else-if="authenticated && userType === 'admin'" :href="route('dashboard')" class="nav-link">Dashboard</Link>
        <Link 
          v-if="authenticated"
          class="nav-link"
					:href="route('logout')" 
					method="post" 
					as="button"
          style="width: 100%"
        >
          Log out
        </Link>
        <Link v-else :href="route('login')" class="nav-link">Login</Link>
        <a href="promo.html" class="nav-link">Promos</a>
        <a href="loyalty-card.html" class="nav-link">Loyalty Card</a>
        <a href="about.html" class="nav-link">About Us</a>
        <a href="contact.html" class="nav-link">Contact Us</a>
      </div>
    </div>
  </main>
</template>

<style scoped>
/* Overlay navigation links */
.overlay {
	height: 0%;
  width: 100%;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background: linear-gradient(45deg, #eeb405 10%, #dc3545 100%);
  overflow-y: hidden;
  transition: 0.5s;
}

.overlay-open {
  height: 100%;
}

@media screen and (max-height: 450px) {
  .overlay {
    overflow-y: auto;
  }
  .overlay a {
    font-size: 20px
  }
}

.nav-link {
  text-decoration: none;color: white!important;font-size: 45px;display: block;transition: .2s;font-weight: bold;padding: 20px;text-decoration: none!important;
}

.nav-link:hover {
  background: #ef8803;
}
  
.nav-head {
  width: 63px;
  margin-left: 30px;
  position: absolute;
  top: 16px;
  left: 0;
}

.nav-a-content {
  margin-right: auto;
  margin-left: auto;
  position: relative;
  top: 50%;
  transform: translateY(-50%);
  font-family: 'Montserrat',sans-serif;
  text-align: center;
  width: 700px;
  z-index: 1
}

/* ============================================ */
/* Hamburger button related */
.open-close-btn {
  position: absolute;
  top: 27px;
  right: 40px;
  text-decoration: none;
  z-index:10;
}

#hamburger-icon{
  display: block;
  width: 50px;
  height: 39px;
  -webkit-transform: rotate(0deg);
  -moz-transform: rotate(0deg);
  -o-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-transition: .5s ease-in-out;
  -moz-transition: .5s ease-in-out;
  -o-transition: .5s ease-in-out;
  transition: .5s ease-in-out;
  cursor: pointer;
}

#hamburger-icon span {
	display: block;
  position: absolute;
  height: 7px;
  width: 100%;
  background: #f8f9fa;
  border-radius: 3px;
  opacity: 1;
  left: 0;
  -webkit-transform: rotate(0deg);
  -moz-transform: rotate(0deg);
  -o-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-transition: .25s ease-in-out;
  -moz-transition: .25s ease-in-out;
  -o-transition: .25s ease-in-out;
  transition: .25s ease-in-out;
}

/* Hamburger Lines  */
#hamburger-icon span:nth-child(1) {
  top: 0px;
}

#hamburger-icon span:nth-child(2), #hamburger-icon span:nth-child(3) {
  top: 16px;
}

#hamburger-icon span:nth-child(4) {
  top: 32px;
}

#hamburger-icon.hamburger-open span{background:#fff}

#hamburger-icon.hamburger-open span:nth-child(1) {
  top: 18px;
  width: 0%;
  left: 50%;
}

#hamburger-icon.hamburger-open span:nth-child(2) {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
}

#hamburger-icon.hamburger-open span:nth-child(3) {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
}

#hamburger-icon.hamburger-open span:nth-child(4) {
  top: 18px;
  width: 0%;
  left: 50%;
}
</style>