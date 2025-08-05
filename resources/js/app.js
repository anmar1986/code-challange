import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia'; // Import createPinia
import App from './vueJs/App.vue';
import router from './router'; // i will create this file later for the router ....

const app = createApp(App);
const pinia = createPinia();

app.use(pinia); 
app.use(router);

app.mount('#app');
