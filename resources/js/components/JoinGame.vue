<template>
    <h2>Jugar partida</h2>

    <p>Partida numero {{ $route.params.idPartida }}</p>




</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        return {
            idPartida: '',
            idAnfitrion: '',
            usuarios: []
        }
    },
    mounted() {
        this.idPartida = this.$route.params.idPartida;
        this.idAnfitrion = this.$route.params.idJugador;

        let echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true
        })
        console.log(this.idAnfitrion)
        echo.channel('join.game.'+this.idPartida).listen('JoinGame',(data)=>{
            console.log(data)
        });
    },
    methods: {
        getAnfitrionPartida(){
            return 1;
        }
    }
}
</script>
