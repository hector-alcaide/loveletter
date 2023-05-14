<template>
    <div class="text-center mt-lg-3">
        <a href="Home.vue"><img class="logo" src="../../images/logo.png"></a>
    </div>
    <div class="divReturn">
        <button class="d-block return buttonClose" @click="$router.push('/home')">Volver</button>
    </div>
    <div class="modal fade" id="modalFriends" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Amigos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text">
                        </div>
                        <div class="col-md-6">
                            <button v-if="this.game && this.game.idHost == this.idUser" type="button" class="mx-auto" @click="prepareGame(this.idGame)">Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="this.loadingData" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div class="container">
    <div class="marker">
        <div id="markerContent" class="markerContent" >
            <div class="text-center">
                <a class="text-1 fs-3 mt-lg-2 mb-lg-4 logout" @click="logout">Logout</a>
            </div>
            <div class="requestFriend" v-if="requestAlias !== ''">
                <div class="text-center my-lg-3" v-for="item in arrayRequests">
                    <label class="text-1 fs-5">Solicitud Amistad de {{item.alias}}</label>
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
            <img @click="marker" src="../../images/marker.png">
            <label v-if="contador > 0" @click="marker" class="text-2 fs-2">{{contador}}</label>
        </div>
    </div>
    <div class="bg-image1">
        <div class="text-center">
            <a href="Home.vue"><img class="logo" src="../../images/logo.png"></a>
        </div>
        <div class="w-100 float-left d-flex">
            <div class="w-50 float-left">
                <button class="return button_secondary" @click="$router.push('/games')">Volver</button>
            </div>
            <div class="w-50 float-left ps-5">
                <h1 class="ps-5 ms-2 title-page">Partida {{this.game.idGame}}</h1>
            </div>
        </div>
        <div class="mx-auto mt-lg-1 text-center w-75 float-left d-flex">
            <div class="w-50">
                <img class="ps-5 ms-2" src="../../images/baron_background.webp" alt="Imagen decorativa Barón">
            </div>
            <div v-if="this.game" class="w-50 mt-2 pt-3">
                <h2 class="text-1 mb-4 display-6">Jugadores</h2>
                <div class="parent-container">
                    <div v-for="user in users" class="py-1 child-container">
                        <p class="text-1 lead my-0 pe-1">{{ user.alias }}</p>
                        <img v-if="this.game.idHost == user.idUser" class=" icon-host" src="../../images/crown_host.png" alt="Icono corona, al ser anfitrión" title="Jugador Anfitrión">
                    </div>
                </div>
                <button v-if="this.game && this.game.idHost == this.idUser && this.users.length > 1" class="mt-5 button-long" @click="prepareGame(this.idGame)">Empezar partida</button>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        this.contador = 0;
        return {
            idGame: this.$route.params.idGame,
            loadingData: true,
            idUser: window.Laravel.user.idUser,
            game: null,
            users: [],
            listGames: [],
            arrayRequests: [],
            requestAlias: "",
            requestId: "",
            solicitud: "",
            friends: [],
            echo: new Echo({
                broadcaster: 'pusher',
                key: 'local',
                cluster: 'mt1',
                wsHost: '127.0.0.1',
                wsPort: 6001,
                forceTLS: false,
                disableStats: true
            })
        }
    },
    beforeMount(){
        this.assignGameData();
    },
    mounted() {
        this.echo.join('join.game.'+this.idGame)
            .here((users) => {
                this.users = users.sort((a, b) => new Date(a.channel_conn_date) - new Date(b.channel_conn_date));
                this.loadingData = false;
                console.log('users conectados:')
                console.log(this.users)
            })
            .joining((user) => {
                this.users.push(user);
            })
            .leaving((user) => {
                this.deleteUserConnected(user.idUser);
            })
            .listen('PrepareGame',(data)=>{
                console.log(data)
                window.location.href = "/games/play/"+data.idGame;
            });
    },
    beforeUnmount(){
        this.echo.leave('join.game.'+this.game.idGame);
    },
    methods: {
        assignGameData(){
            this.$axios.post('/api/getgamedata', {
                idGame: this.idGame,
            }).then(response => {
                //TODO: mostrar un mensaje al usuario en la lista de games conforme esa game esta llena
                if (response.data.started == 1){
                    window.location.href = "/games";
                }else{
                    this.game = response.data;
                    console.log(this.game)
                }
            });
        },
        deleteUserConnected(idUser){
            let array_pos = this.users.map(item => item.idUser).indexOf(idUser);
            this.users.splice(array_pos, 1);
        },
        prepareGame(idGame){
            let ids_players = this.users.map(item => item.idUser);
            this.$axios.post('/api/preparegame', {
                idGame: idGame,
                ids_players: ids_players,
            }).then(response => {
                console.log(response)
            });
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
        listFriends(){
            this.$axios.get('/api/getfriends').then(response => {
                console.log(response.data)
                this.friends = response.data;
            });
        }
    }
}
</script>
