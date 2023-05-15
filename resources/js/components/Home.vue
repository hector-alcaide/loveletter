<template>
    <div class="container">
        <div class="marker" >
            <div id="markerContent" class="markerContent" >
                <div class="text-center">
                    <a class="text-2 fs-3 mt-lg-2 mb-lg-4 logout" @click="logout">Logout</a>
                </div>
                <div class="requestFriend" v-if="requestAlias !== ''">
                    <div class="text-center my-lg-3" v-for="item in arrayRequests">
                        <label class="text-2 fs-5">Solicitud Amistad de {{item.alias}}</label>
                        <form class="d-inline mx-1" @submit.prevent="acceptInvitation(item.id)">
                            <button class="shield d-inline mx-4" type="submit"><div class="shield_yes mt-lg-1"></div></button>
                        </form>
                        <form class="d-inline mx-1" @submit.prevent="rejectInvitation(item.id)">
                            <button class="shield d-inline mx-4" type="submit"><div class="shield_no mt-lg-1"></div></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="markerLabel">
                <img @click="marker" src="../../images/marker.svg">
                <label v-if="contador > 0" @click="marker" class="text-2 fs-2">{{contador}}</label>
            </div>
        </div>
        <div class="bg-image1">
            <div class="text-center">
                <img class="logo" src="../../images/logo.svg">
            </div>
            <div class="text-center mt-lg-4">
                <div class="d-inline-block me-lg-5" style="padding-bottom: 8rem;">
                    <img src="../../images/left_guard.svg">
                </div>
                <div class="d-inline-block align-middle text-center mt-lg-3 pb-lg-5 mx-lg-5">
                    <button class="button_jugar d-block mx-auto mt-lg-5" @click="$router.push('/games')">Jugar</button>
                    <button class="button_menu d-block mx-auto mt-lg-5 mb -lg-4" @click="$router.push('/profile')">Perfil</button>
                    <button class="button_menu d-block mx-auto my-lg-4" @click="$router.push('/friends')">Amigos</button>
                    <button class="button_menu d-block mx-auto my-lg-4" @click="$router.push('/ranking')">Clasificación</button>
                </div>
                <div class="d-inline-block ms-lg-5">
                    <img src="../../images/right_guard.svg">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
export default {
    name: "Home",
    data() {
        this.contador = 0
        return {
            arrayRequests: [],
            requestAlias: "",
            requestId: "",
            solicitud: ""
        }
    },
    created() {
    },
    mounted(){
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
        listInvitations(){
            this.$axios.post('/api/listInvitations', {
                idReceptor: window.Laravel.user.idUser
            }).then(response => {
                console.log(response);
            });
        }
    }
}
</script>


