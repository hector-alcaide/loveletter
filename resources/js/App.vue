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

    <div class="container background bg-image1">
        <div class="marcador" v-if="isLoggedin">
            <div id="marcador_content" style="display: none">
                <div class="text-center">
                    <a class="text-1 fs-3 my-lg-2 logout" @click="logout">Logout</a>
                </div>
                <div class="solicitudAmistad" v-if="solicitudAlias !== ''">
                    <div class="text-center" v-for="item in arraySolicitudes">
                        <label class="text-1 fs-5">Solicitud Amistad de {{item.alias}}</label>
                        <form class="d-inline" @submit.prevent="aceptarInvitacion(item.id)">
                            <button class="button_aceptar d-inline" type="submit">Aceptar</button>
                        </form>
                        <form class="d-inline mx-2" @submit.prevent="rechazarInvitacion(item.id)">
                            <button class="button_rechazar d-inline">Rechazar</button>
                        </form>
                    </div>
                </div>
            </div>
            <img @click="marcador" src="../images/marcador.png">
        </div>

        <router-view></router-view>
    </div>
</template>
<script>
import Echo from 'laravel-echo';
import Pusher from "pusher-js";
export default {
    name: "App",
    data() {
        this.cont = 0
        return {
            isLoggedin: false,
            arraySolicitudes: [],
            solicitudAlias: "",
            solicitudId: "",
            solicitud: ""
        }
    },
    created() {
        if(window.Laravel.isLoggedin){
            this.isLoggedin =true;
        }
    },
    mounted(){
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('api/solicitudAmistad', {
            })
                .then(response => {
                    console.log(response)
                    this.arraySolicitudes = response.data;

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
                this.$axios.post('api/logout')
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
        }
    },
}
</script>
