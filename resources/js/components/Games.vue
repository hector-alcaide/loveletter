<template>
    <div class="modal fade" id="invitationsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content pb-5">
                <div class="modal-header border-0 pe-1">
                    <div class="modal-div-title">
                        <h2 class="modal-title" id="staticBackdropLabel">Invitaciones</h2>
                    </div>
                    <button type="button" class="mx-lg-3 close-modal" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body pb-0">
                    <div class="text-center px-3">
                        <div class="text-2 fs-4 mx-lg-4" v-for="inv in invitations">
                            <label class="text-2 mt-lg-1 pb-2">- {{inv.sender.alias}} te invita a una partida</label>
                            <div class="pb-4 mb-3">
                                <button type="button" class="mx-lg-3 fs-5" @click="updateInvitation(inv.idInvitation, inv.idGame, 1)">Aceptar</button>
                                <button type="button" class="mx-lg-3 fs-5 button_secondary" @click="updateInvitation(inv.idInvitation, inv.idGame, 0)">Rechazar</button>
                            </div>
                        </div>
                        <div v-if="invitations && invitations.length == 0">
                            <label class="text-2 mt-lg-1 pb-2 fs-4">No tienes invitaciones</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        <div>
            <button class="invitation-button" type="button" data-bs-toggle="modal" data-bs-target="#invitationsModal" title="Abrir invitaciones" @click="listInvitations()">
                Invitaciones
            </button>
        </div>
        <div class="bg-image1">
            <div class="text-center">
                <a href="/"><img class="logo" src="../../images/logo.svg"></a>
            </div>
            <div class="w-100 float-left d-flex me-">
                <div class="w-50 float-left">
                    <button class="return button_secondary" @click="$router.push('/')">Volver</button>
                </div>
                <div class="w-50 float-left ps-5">
                    <h1 class="ps-5 ms-2 title-page">Partidas</h1>
                </div>
            </div>
            <div class="d-flex justify-content-center w-100 mx-auto">
                <button class="button_jugar mt-lg-3 mb-4" @click="$router.push('/games/create')">Crear partida</button>
            </div>
            <div class="w-100">
                <div class="mx-auto w-75">
                    <carousel :items-to-show="1">
                        <slide v-for="item in listGames" :key="item.idGame">
                            <div class="text-center mb-5 d-inline-block mx-2" style="width: 250px">
                                <label class="text-2 fs-4">Partida número {{item.idGame}}</label>
                                <img class="my-2" src="../../images/imagen-partida-peq.jpg" style="width: 200px">
                                <button type="submit" class="mx-auto py-1 px-3" @click="$router.push('/games/join/'+item.idGame)">Unirse</button>
                            </div>
                        </slide>

                        <template #addons>
                            <navigation />
                            <pagination />
                        </template>
                    </carousel>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Echo from "laravel-echo";
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default {
    name: 'App',
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        this.contador = 0;
        return {
            listGames: [],
            arrayRequests: [],
            requestAlias: "",
            requestId: "",
            solicitud: "",
            invitations: null
        }
    },
    beforeMount() {
        this.listInvitations();
    },
    mounted() {
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
        this.getGamesList();
        let echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: this.ipHost,
            wsPort: 6001,
            forceTLS: false,
            disableStats: true
        })

        echo.channel('games.list').listen('CreateGame',(data)=>{
            this.listGames.push(data);
            console.log(this.listGames)
        });

        // this.connectChannel_ListGames();
        // echo.channel('game.'+ this.game_id).listen('CreateGame',(e)=>{
        //     console.log('go GameAction');
        //     //code for displaying the serve data
        //     console.log(e); // the data from the server
        // });
    },
    methods: {
        getGamesList(){
            this.$axios.post('api/getgameslist')
                .then(response => {
                    this.listGames = response.data;
                    console.log(this.listGames);
                }
            );
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
        listInvitations(){
            this.$axios.post('/api/listInvitations', {
                idReceptor: window.Laravel.user.idUser
            }).then(response => {
                console.log(response);
                this.invitations = response.data.invitations ?? null;
            });
        },
        updateInvitation(idInvitation, idGame, status){
            this.$axios.post('/api/updateInvitation', {
                idInvitation: idInvitation
            }).then(response => {
                console.log(response);
                if (status == 1){
                    window.location.href = "/games/join/" + idGame;
                }else{
                    this.listInvitations();
                }
            });
        }
    }
}
</script>
