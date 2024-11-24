import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';


import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import { Link, Head, } from '@inertiajs/vue3'
import {ZiggyVue} from '../../vendor/tightenco/ziggy'
import ToastService from 'primevue/toastservice';
import StyleClass from 'primevue/styleclass';
import Ripple from 'primevue/ripple';
import Tooltip from 'primevue/tooltip';
import ConfirmationService from 'primevue/confirmationservice';

createInertiaApp({
  title: (title) => `${title}`,
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(ToastService)
      .use(ConfirmationService)
      .component('Head', Head)
      .component('Link', Link)
      .directive('tooltip', Tooltip)
      .directive('styleclass', StyleClass)
      .directive('ripple', Ripple)
      .use(PrimeVue, {
        ripple: true,
        theme: {
            preset: Aura,
            options: {
                darkModeSelector: 'light'
            }
        }
      })
      .mount(el)
  },
  progress: {
    color: 'red',
    includeCSS: true,
    showSpinner: true,
  },
})