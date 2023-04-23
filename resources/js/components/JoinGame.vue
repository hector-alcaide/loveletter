<template>
    <div v-if="this.cargandoDatos" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="this.partida">
        <h2>Partida {{this.partida.idPartida}} </h2>
        <p>Anfitrion: {{this.partida.idAnfitrion}}</p>
        <h3>Jugadores</h3>
        <div v-for="item in usuarios" :key="item.id">
            <p>{{ item.alias }}</p>
        </div>
    </div>

<!--    <p>Partida numero {{ idPartida }}</p>-->
<!--    <p>Anfitrion {{ idAnfitrion }}</p>-->



    <button v-if="this.partida && this.partida.idAnfitrion == this.idUsuario" class="mx-auto" @click="prepareGame(this.idPartida)">Empezar partida</button>

    <!--    <div v-for="item in items" :key="item.id">-->
<!--        {{ item.name }}-->
<!--    </div>-->

</template>

<script>
import Echo from "laravel-echo";

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
        this.echo.join('join.game.'+this.idPartida)
            .here((users) => {
                this.usuarios = users.sort((a, b) => new Date(a.conexion_canal) - new Date(b.conexion_canal));
                this.cargandoDatos = false;
                console.log('usuarios conectados:')
                console.log(this.usuarios)
            })
            .joining((user) => {
                this.usuarios.push(user);
            })
            .leaving((user) => {
                this.deleteUserConnected(user.idUsuario);
            })
            .listen('PrepareGame',(data)=>{
                console.log(data)
                window.location.href = "/games/play/"+data.idPartida;
            });
    },
    beforeUnmount(){
        this.echo.leave('join.game.'+this.partida.idPartida);
    },
    methods: {
        assignPartidaData(){
            this.$axios.post('/api/getgamedata', {
                idPartida: this.idPartida,
            }).then(response => {
                //TODO: mostrar un mensaje al usuario en la lista de partidas conforme esa partida esta llena
                if (response.data.empezada == 1){
                    window.location.href = "/games";
                }else{
                    this.partida = response.data;
                    console.log(this.partida)
                }
            });
        },
        deleteUserConnected(idUsuario){
            let array_pos = this.usuarios.map(item => item.idUsuario).indexOf(idUsuario);
            this.usuarios.splice(array_pos, 1);
        },
        prepareGame(idPartida){
            let ids_jugadores = this.usuarios.map(item => item.idUsuario);
            this.$axios.post('/api/preparegame', {
                idPartida: idPartida,
                ids_jugadores: ids_jugadores,
            }).then(response => {
                console.log(response)
            });
        },
    }
}
</script>
