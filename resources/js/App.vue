<template>
    <div class="bg-color1" v-if="isLoggedin">
        <div v-if="ruta != 'http://127.0.0.1:8000/board'" class="marker" v-on:mouseover="mouseOver">
            <div id="markerContent" v-on:mouseover="mouseOver" style="display: none">
                <div class="text-center">
                    <a class="text-1 fs-3 mt-lg-2 mb-lg-4 logout" @click="logout">Logout</a>
                </div>
                <div class="requestFriend" v-if="requestAlias !== ''">
                    <div class="text-center my-lg-3" v-for="item in arrayRequests">
                        <label class="text-1 fs-5">Solicitud Amistad de {{item.alias}}</label>
                        <form class="d-inline" @submit.prevent="acceptInvitation(item.id)">
                            <button class="shield d-inline px-3" type="submit"><div class="shield_yes"></div></button>
                        </form>
                        <form class="d-inline mx-2" @submit.prevent="rejectInvitation(item.id)">
                            <button class="shield d-inline px-3" type="submit"><img style="width: 35px" src="../images/shield_no.png"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="markerLabel">
                <img @click="marker" src="../images/marker.png">
                <label v-if="contador > 0" @click="marker" class="text-2 fs-2">{{contador}}</label>
            </div>
        </div>
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
        this.cont = 0,
        this.contador = 0
        return {
            isLoggedin: false,
            arrayRequests: [],
            requestAlias: "",
            requestId: "",
            solicitud: "",
            ruta: "",
            active: false,
        }
    },
    created() {
        if(window.Laravel.isLoggedin){
            this.isLoggedin =true;
        }
    },
    mounted(){
        //this.ruta = this.$route.path      http://127.0.0.1:8000/games/join
        this.ruta = window.location.href

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('/api/requestFriend', {
            })
                .then(response => {
                    console.log(response)
                    this.arrayRequests = response.data;
                    this.contador = response.data.length;

                    response.data.forEach(res =>{
                        this.requestAlias = res.alias;
                        this.requestId = res.id;
                    });
                })
                .catch(function (error) {
                    console.error(error);
                });
        });

    },
    methods: {
        mouseOver: function() {
        this.active = !this.active;
        if(this.active == true){
            document.getElementById('markerContent').style.display = 'block';
        }else{  
            document.getElementById('markerContent').style.display = 'none';
        }
        },
        logout(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        marker(){
            if (this.cont == 0){
                document.getElementById('markerContent').style.display = 'block';
                this.cont = 1
            }else{
                document.getElementById('markerContent').style.display = 'none';
                this.cont = 0
            }
        },
        acceptInvitation(requestId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/acceptRequestInvitation', {
                    solicitud: requestId
                })
                    .then(response => {
                        console.log(response)
                        location.reload();
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },
        rejectInvitation(requestId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/rejectRequestInvitation', {
                    solicitud: requestId
                })
                    .then(response => {
                        console.log(response)
                        location.reload();
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },
    },
}
</script>
