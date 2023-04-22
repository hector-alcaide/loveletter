import {createWebHistory, createRouter} from "vue-router";

import Home from '../components/Home.vue';
import Register from '../components/Register.vue';
import Login from '../components/Login.vue';
import Games from '../components/Games.vue';
import CreateGame from '../components/CreateGame.vue';
import JoinGame from '../components/JoinGame.vue';
import PlayGame from '../components/PlayGame.vue';

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
        component: Games,
        meta:{
            requiresAuth: true
        }
    },
    {
        name: 'createGame',
        path: '/games/create',
        component: CreateGame,
        meta:{
            requiresAuth: true
        },
    },
    {
        name: 'joinGame',
        path: '/games/join/:idPartida',
        component: JoinGame,
        meta:{
            requiresAuth: true
        },
    },
    {
        name: 'playGame',
        path: '/games/play/:idPartida',
        component: PlayGame,
        meta:{
            requiresAuth: true
        },
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
