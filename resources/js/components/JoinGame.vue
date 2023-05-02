<template>
    <div v-if="this.loadingData" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="this.game">
        <h2>Partida {{this.game.idGame}} </h2>
        <p>Anfitrion: {{this.game.idHost}}</p>
        <h3>Jugadores</h3>
        <div v-for="item in users" :key="item.id">
            <p>{{ item.alias }}</p>
        </div>
    </div>

<!--    <p>Partida numero {{ idGame }}</p>-->
<!--    <p>Anfitrion {{ idHost }}</p>-->



    <button v-if="this.game && this.game.idHost == this.idUser" class="mx-auto" @click="prepareGame(this.idGame)">Empezar game</button>

    <!--    <div v-for="item in items" :key="item.id">-->
<!--        {{ item.name }}-->
<!--    </div>-->

</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        return {
            idGame: this.$route.params.idGame,
            loadingData: true,
            idUser: window.Laravel.user.idUser,
            game: null,
            users: [],
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
    }
}
</script>
