<template>
    <div class="bg-color1" v-if="isLoggedin">
        <router-view></router-view>
    </div>
    <div class="bg-color2" v-if="isLoggedin == false">
        <router-view></router-view>
    </div>
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
        //this.ruta = this.$route.path      http://127.0.0.1:8000/games/join
        //this.ruta = window.location.href    
        // v-on:mouseover="mouseOver"
        // mouseOver: function() {
        // this.active = !this.active;
        //     if(this.active == true){
        //         document.getElementById('markerContent').style.display = 'block';
        //     }else{
        //         document.getElementById('markerContent').style.display = 'none';
        //     }
        // },    

    },
    methods: {         
        
    },
}
</script>
