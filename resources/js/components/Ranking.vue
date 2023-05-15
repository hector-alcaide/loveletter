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
                <a href="Home.vue"><img class="logo" src="../../images/logo.svg"></a>        
            </div>
            <div class="divReturn">
                <button class="d-inline return buttonClose" @click="$router.push('/home')">Volver</button>
                <h1 class="text-2 title-profile" style="margin-right: 150px">Clasificación</h1>
            </div>
            <div class="text-center mx-auto" style="width: 90%; margin-top: 2rem; height: 453px;">                
                <div class="table-responsive d-inline-block">
                    <table class="table" style="width: 550px;">
                    <thead>
                        <tr>                    
                            <th class="text-center text-2 fs-2 px-3">Jugador</th>
                            <th class="text-center text-2 fs-2 px-3">Victorias totales</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr v-for="item in rankingPlayer">
                        <td class="text-center"><label class="text-2 fs-4 fw-bold color_b">{{ item.alias }}</label></td>
                        <td class="text-center"><label class="text-2 fs-4 fw-bold color_b">{{ item.games }}</label></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <div class="d-inline-block">
                    <img class="chanciller" src="../../images/canciller.svg">
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
            solicitud: "",
            rankingPlayer: []
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
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('/api/topwinners', {
            })
                .then(response => {
                    this.rankingPlayer = response.data;                
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


