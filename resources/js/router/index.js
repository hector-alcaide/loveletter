import {createWebHistory, createRouter} from "vue-router";

import Home from '../components/Home.vue';
import Register from '../components/Register.vue';
import Login from '../components/Login.vue';
import CreateGame from '../components/CreateGame.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
        meta:{
            requiresAuth: true
        }
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'games',
        path: '/games',
        redirect: '/games/create',
        component: CreateGame,
        meta:{
            requiresAuth: true
        },
        children: [
            {
                name: 'create',
                path: 'create',
                component: CreateGame,
            },
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/'
    }
];

const router = createRouter({
    history:createWebHistory(),
    routes: routes,
});

router.beforeEach((to, from, next) => {

    console.log(window.Laravel.isLoggedin);
    if (to.matched.some(record => record.meta.requiresAuth) && (window.Laravel.isLoggedin === false)) {
        next('/login')
    } else {
        next()
    }
});

export default router;
