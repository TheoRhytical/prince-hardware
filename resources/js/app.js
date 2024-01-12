// Import our custom CSS
import '/resources/scss/styles.scss';
// Import all of Bootstrap's JS
// import * as bootstrap from 'bootstrap'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// Quasar
import { Quasar } from 'quasar'

// Import icon libraries
import '@quasar/extras/material-icons/material-icons.css'

// Import Quasar css
import 'quasar/src/css/index.sass'

// Import Quasar icons
// import iconSet from 'quasar/icon-set/fontawesome-v6'
// import '@quasar/extras/fontawesome-v6/fontawesome-v6.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(Quasar, {
        // iconSet: iconSet,
        plugins: {}, // import Quasar plugins and add here
      })
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
