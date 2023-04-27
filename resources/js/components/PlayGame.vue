<template>
    <div v-if="this.cargandoDatos" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="this.partida">
        <h2>Jugar partida</h2>

        <h3>Test Jugadores</h3>
        <div v-for="item in this.partida.jugadores" :key="item.id">
            <p>{{ item.alias }}</p>
        </div>

        <h3>Turno de {{this.partida.jugadores[this.partida.numJugadorTurno].alias}} </h3>
        <div v-if="this.partida.numJugadorTurno == this.partida.jugadores[this.idUsuario].numJugador">
            <button class="mx-auto" @click="robarCarta(this.idPartida)">
                Robar carta
            </button>
        </div>





    </div>
</template>

<script>
import Echo from "laravel-echo";
import callback from "pusher-js/src/core/events/callback";

export default {
    data() {
        return {
            idPartida: this.$route.params.idPartida,
            cargandoDatos: true,
            idUsuario: window.Laravel.user.idUsuario,
            partida: null,
            usuarios: [],
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
        this.assignPartidaData();
    },
    mounted() {
        this.echo.join('play.game.'+this.idPartida)
            .here((users) => {
                this.usuarios = users;
                if(Object.keys(this.partida.jugadores).length == this.usuarios.length){
                    console.log('empezar partida')
                    this.jugarTurno();
                }else{
                    console.log('faltan jugadores por unirse')
                }
            })
            .joining((user) => {
                this.usuarios.push(user);
                console.log(this.usuarios)
                if(Object.keys(this.partida.jugadores).length == this.usuarios.length){
                    console.log('empezar partida')
                    this.jugarTurno();
                }else{
                    console.log('faltan jugadores por unirse')
                }
            })
            .leaving((user) => {
                this.deleteUserConnected(user.idUsuario);
            })
            .listen('PublicActionUser',(data)=>{
                console.log(data);
                this.refreshPartidaData();
                console.log("hola")

            });

    },
    beforeUnmount(){
        this.echo.leave('play.game.'+this.idPartida);
    },
    methods: {
        assignPartidaData(){
            this.$axios.post('/api/getgamedata', {
                idPartida: this.idPartida,
            }).then(response => {
                if (response.data.empezada == 1 && response.data.jugadores.some(el => el.idUsuario === this.idUsuario) ){
                    this.partida = JSON.parse(response.data.partida);
                    // this.partida = response.data.partida;
                    this.cargandoDatos = false;
                    console.log(this.partida)
                }else{
                    window.location.href = "/games";
                }
            });
        },
        deleteUserConnected(idUsuario){
            let array_pos = this.usuarios.map(item => item.idUsuario).indexOf(idUsuario);
            this.usuarios.splice(array_pos, 1);
        },
        jugarTurno(){

            let jugadorTurno = this.partida.numJugadorTurno;
            let numJugador = this.partida.jugadores[this.idUsuario].numJugador;

            console.log(jugadorTurno)
            console.log(numJugador)
            if(jugadorTurno == numJugador){
                console.log('te toca jugar');
            }else{
                console.log('otro jugador juega el turno')
            }
        },
        robarCarta(){
            this.$axios.post('/api/stealcard', {
                partida: this.partida,
                idUsuario: this.idUsuario
            }).then(response => {
               // this.partida = response.data;
                console.log(response)
            });
        },
        refreshPartidaData(){
            this.$axios.post('/api/getgamedata', {
                idPartida: this.idPartida,
            }).then(response => {
                this.partida = response.data;
                console.log(this.partida)

            });
        }
    }
}
</script>
