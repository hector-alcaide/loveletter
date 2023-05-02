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
    <div class="tablero container" v-if="ruta == 'http://127.0.0.1:8000/board'">
        <router-view></router-view>
    </div>
    <div v-else>
    <div class="container background bg-image1" v-if="isLoggedin">
        <div class="marcador">
            <div id="marcador_content" style="display: none">
                <div class="text-center">
                    <a class="text-1 fs-3 mt-lg-2 mb-lg-4 logout" @click="logout">Logout</a>
                </div>
                <div class="solicitudAmistad" v-if="solicitudAlias !== ''">
                    <div class="text-center my-lg-3" v-for="item in arraySolicitudes">
                        <label class="text-1 fs-5">Solicitud Amistad de {{item.alias}}</label>
                        <form class="d-inline" @submit.prevent="aceptarInvitacion(item.id)">
                            <button class="shield d-inline px-3" type="submit"><img style="width: 35px" src="../images/shield5.png"></button>
                        </form>
                        <form class="d-inline mx-2" @submit.prevent="rechazarInvitacion(item.id)">
                            <button class="shield d-inline px-3" type="submit"><img style="width: 35px" src="../images/shield6.png"></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="marcador_etiqueta">
                <img @click="marcador" src="../images/marcador.png">
                <label v-if="contador > 0" @click="marcador" class="text-2 fs-2">{{contador}}</label>
            </div>
        </div>
        <router-view></router-view>
    </div>
    <div class="container background" v-if="isLoggedin == false">
        <router-view></router-view>
    </div>
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
            arraySolicitudes: [],
            solicitudAlias: "",
            solicitudId: "",
            solicitud: "",
            erre: "",
            ruta: ""
        }
    },
    created() {
        if(window.Laravel.isLoggedin){
            this.isLoggedin =true;
        }
    },
    mounted(){
        this.erre = this.$route.path
        this.ruta = window.location.href

        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('api/solicitudAmistad', {
            })
                .then(response => {
                    console.log(response)
                    this.arraySolicitudes = response.data;
                    this.contador = response.data.length;

                    response.data.forEach(res =>{
                        this.solicitudAlias = res.alias;
                        this.solicitudId = res.id;
                    });
                })
                .catch(function (error) {
                    console.error(error);
                });
        });

    },
    methods: {
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
        marcador(){
            if (this.cont == 0){
                document.getElementById('marcador_content').style.display = 'block';
                this.cont = 1
            }else{
                document.getElementById('marcador_content').style.display = 'none';
                this.cont = 0
            }
        },
        aceptarInvitacion(solicitudId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/aceptarSolicitudAmistad', {
                    solicitud: solicitudId
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
        rechazarInvitacion(solicitudId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/rechazarSolicitudAmistad', {
                    solicitud: solicitudId
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
