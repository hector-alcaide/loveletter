<template>
    <h2>Jugar partida</h2>

    <p>Partida numero {{ idPartida }}</p>
    <p>Anfitrion {{ idJugador1 }}</p>



    <button v-if="this.idJugador1 == this.idUsuario" class="mx-auto">Empezar partida</button>

    <div v-for="item in items" :key="item.id">
        {{ item.name }}
    </div>

</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        return {
            idPartida: this.$route.params.idPartida,
            idJugador1: this.$route.params.idJugador1,
            idUsuario: window.Laravel.user.idUsuario,
            usuarios: []
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

        echo.channel('join.game.'+this.idPartida).listen('JoinGame',(data)=>{
            console.log(data)
        });
    },
    methods: {
    }
}
</script>
