<template>
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
            <div class="divReturn">
                <button class="d-inline return button_secondary" @click="$router.push('/home')">Volver</button>
                <h1 class="text-1 title-profile">Partidas</h1>
            </div>
            <div class="d-flex justify-content-center w-100">
                <button class="button_jugar mt-lg-4" @click="$router.push('/games/create')">Crear partida</button>
            </div>
            <div class="w-100">
                <div class="mx-auto w-75">
                    <carousel :items-to-show="1">
                        <slide v-for="item in listGames" :key="item.idGame">
                            <div class="text-center mb-5 d-inline-block mx-2" style="width: 250px">
                                <label class="text-2 fs-4">Partida número {{item.idGame}}</label>
                                <img class="my-2" src="../../images/imagen-partida-peq.jpg" style="width: 200px">
                                <button type="submit" class="mx-auto" @click="$router.push('/games/join/'+item.idGame)">Unirse a partida</button>
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
            solicitud: ""
        }
    },
    mounted() {
        this.getGamesList();
        let echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: '127.0.0.1',
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
    }
}
</script>
