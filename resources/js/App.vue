<template>
<!--    <div class="container">-->
<!--        <nav class="navbar navbar-expand-sm navbar-light bg-light mb-4">-->
<!--            <a class="navbar-brand" href="#">Laravel Vue 3</a>-->
<!--            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"-->
<!--                    aria-expanded="false" aria-label="Toggle navigation"></button>-->
<!--            <div class="navbar-nav" v-if="isLoggedin">-->
<!--                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>-->
<!--                <router-link to="/posts" class="nav-item nav-link">xxxx</router-link>-->
<!--                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>-->
<!--            </div>-->
<!--            <div class="navbar-nav" v-else>-->
<!--                <router-link to="/" class="nav-item nav-link">Home</router-link>-->
<!--                <router-link to="/login" class="nav-item nav-link">Login</router-link>-->
<!--                <router-link to="/register" class="nav-item nav-link">Register</router-link>-->
<!--            </div>-->
<!--        </nav>-->
<!--    </div>-->
    <!-- <div class="board container" v-if="ruta == 'http://127.0.0.1:8000/board'">
        <router-view></router-view>v-if="ruta != 'http://127.0.0.1:8000/board'"
    </div>
    <div v-else> -->
    <div class="container background" v-if="isLoggedin">        
        <router-view></router-view>
    </div>
    <div class="container background" v-if="isLoggedin == false">
        <router-view></router-view>
    </div>
    <!-- </div> -->
</template>
<script>
import Echo from 'laravel-echo';
import Pusher from "pusher-js";
export default {
    name: "App",
    data() {        
        return {
            isLoggedin: false,            
        }
    },
    created() {
        if(window.Laravel.isLoggedin){
            this.isLoggedin =true;
        }
    },
    mounted(){ 
        
        //this.inMarker = true;
        //this.ruta = this.$route.path      http://127.0.0.1:8000/games/join
        //this.ruta = window.location.href        

    },
    methods: {         
        
    },
}
</script>
