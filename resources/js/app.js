import './bootstrap';

import {createApp} from 'vue';
import App from './App.vue';
import axios from 'axios';
import router from './router';

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.mount('#app');

//app.config.globalProperties.ipHost = '192.168.19.126';
app.config.globalProperties.ipHost = '127.0.0.1';
