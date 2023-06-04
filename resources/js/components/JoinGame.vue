<template>
    <div class="modal fade" id="modalFriends" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content pb-5">
                <div class="modal-header border-0 pe-1">
                    <div class="modal-div-title">
                        <h2 class="modal-title" id="staticBackdropLabel">Invitar amigos</h2>
                    </div>
                    <button type="button" class="mx-lg-3 close-modal" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body pb-3">
                    <div class="text-center px-3">
                        <div class="text-2 fs-4 mx-lg-4 d-inline" v-for="item in friends">
                            <label class="text-2 mt-lg-1 ms-lg-5 pb-4">{{item.friend_id}}</label>
                            <button type="button" class="mx-lg-3 close-modal invite-add" @click="inviteGame(item.friend_id)">+</button>
                        </div>
                        <div>
                            <p class="text-1">{{ invitationMessage }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-3 pt-1">
                </div>
            </div>
        </div>
    </div>
    <div v-if="loadingData" class="pantalla-carga d-flex justify-content-center align-items-center">
        <div class="spinner"></div>
    </div>
    <div class="container">
    <div class="marker">
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
            <a href="/"><img class="logo" src="../../images/logo.svg"></a>
        </div>
        <div class="w-100 float-left d-flex">
            <div class="w-50 float-left">
                <button class="return button_secondary" @click="$router.push('/games')">Volver</button>
            </div>
            <div class="w-50 float-left ps-5">
                <h1 class="ps-5 ms-2 title-page" v-if="this.game">Partida {{this.game.idGame}} <button class="invite" type="button" data-bs-toggle="modal" data-bs-target="#modalFriends" title="Invitar amigos a la partida"></button> </h1>
            </div>
        </div>
        <div class="mx-auto mt-lg-1 text-center w-75 float-left d-flex">
            <div class="w-50">
                <img class="ps-5 ms-2" src="../../images/baron_background.webp" alt="Imagen decorativa Barón">
            </div>
            <div v-if="this.game" class="w-50 mt-2 pt-3">
                <h2 class="text-2 mb-4 display-6">Jugadores</h2>
                <div class="parent-container">
                    <div v-for="user in users" class="py-1 child-container">
                        <p class="text-2 lead my-0 pe-1">{{ user.alias }}</p>
                        <img v-if="this.game.idHost == user.idUser" class=" icon-host" src="../../images/crown_host.svg" alt="Icono corona, al ser anfitrión" title="Jugador Anfitrión">
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
            invitationMessage: null,
            echo: new Echo({
                broadcaster: 'pusher',
                key: 'local',
                cluster: 'mt1',
                wsHost: this.ipHost,
                wsPort: 6001,
                forceTLS: false,
                disableStats: true
            })
        }
    },
    beforeMount(){
        this.assignGameData();
        this.listFriends();
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
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('/api/requestFriend', {
            }).then(response => {
                console.log(response)
                this.arrayRequests = response.data;
                this.contador = response.data.length;

                response.data.forEach(res =>{
                    this.requestAlias = res.alias;
                    this.requestId = res.id;
                });
            }).catch(function (error) {
                console.error(error);
            });
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
        listFriends(e){
            this.$axios.post('/api/yourFriends', {
            })
            .then(response => {
                console.log('response')
                console.log(response)
                    this.friends = response.data;
            })
        },
        inviteGame(aliasFriend){
            this.$axios.post('/api/inviteFriendGame', {
                idSender: this.idUser,
                aliasFriend: aliasFriend,
                idGame: this.game.idGame
            }).then(response => {
                console.log(response)
                this.invitationMessage = response.data.message;
            })
        }
    }
}
</script>
