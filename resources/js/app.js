import './bootstrap';

import {createApp} from 'vue';
import App from './App.vue';
import axios from 'axios';
import router from './router';
import Echo from "laravel-echo";

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.mount('#app');


this.$axios.post('/api/login', {
    email: 'test@test.com',
    password: 'test'
}).then(response => {
    const token = response.data.token;

    // Usa el token para conectarse a Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
        wsPort: 6001,
        cluster: "mt1",
        forceTLS: false,
        disableStats: true,
        authEndpoint :'http://127.0.0.1:8000/api/broadcasting/auth',
        auth:{
            headers: {
                Authorization: 'Bearer '+token,
            }
        }
    });
}).catch(error => {
    console.error(error);
});
