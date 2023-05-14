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
                <button class="d-inline return button_secondary" @click="$router.push('/games')" style="width: 175px;">Lista de partidas</button>
                <h1 class="text-1 title-profile">Crear Partida</h1>
            </div>
            <div class="mx-auto mt-lg-5 text-center" style="width: 65%;">
                <div class="d-inline-block me-lg-5">
                    <img class="" src="../../images/espia_fondo.png" style="width: 250px;">
                </div>
                <div class="text-center d-inline-block ms-lg-5" style="float: right;">
                    <form>
                        <div class="d-block my-lg-5">
                            <label for="type" class="text-1 mx-lg-4 fs-4">Tipo</label>
                            <select class="styleInput mx-lg-4" name="type" id="type" v-model="type" style="width: 140px;">
                                <option value="" disabled>-</option>
                                <option value="public">Pública</option>
                                <option value="private">Privada</option>
                            </select>
                        </div>
                        <div class="d-block my-lg-5">
                            <label for="type" class="text-1 mx-lg-4 fs-4">Victorias por partida</label>
                            <input class="styleInput text-center mx-lg-3" id="numMaxWins" type="number" v-model="numMaxWins" required autofocus autocomplete="off" style="width: 80px;">
                        </div>
                        <div class="mt-lg-4">
                            <button type="submit" class="button-long mx-lg-4" @click="newGame" style="width: 175px;">Crear partida</button>
                        </div>
                    </form>
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
            //CREAR PARTIDA
            type: "public",
            numMaxWins: 3,
            //SALA PREVIA A PARTIDA COMENZADA
            game_id: '',
            listGames: [],
            arrayRequests: [],
            requestAlias: "",
            requestId: "",
            solicitud: ""
        }
    },
    mounted() {
        let echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true
        })
        // echo.channel('game.'+ this.game_id).listen('CreateGame',(e)=>{
        //     console.log('go GameAction');
        //     //code for displaying the serve data
        //     console.log(e); // the data from the server
        // });
    },
    methods: {
        newGame(e){
            e.preventDefault();
            this.$axios.post('/api/newgame', {
                type: this.type,
                numMaxWins: this.numMaxWins,
            }).then(response => {
                console.log(response)
                this.$router.push('/games/join/'+response.data.idGame);
            }).catch(function (error) {
                console.error(error);
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
    }
}
</script>
