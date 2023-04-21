<template>
    <div v-if="this.cargandoDatos" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="this.partida">
        <h2>Jugar partida</h2>
        <p>Anfitrion: {{this.partida.idAnfitrion}}</p>
    </div>

<!--    <p>Partida numero {{ idPartida }}</p>-->
<!--    <p>Anfitrion {{ idAnfitrion }}</p>-->



    <button v-if="this.partida && this.partida.idAnfitrion == this.idUsuario" class="mx-auto">Empezar partida</button>

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
            partida: null
        }
    },
    beforeMount(){
        // this.joinGame();
        this.assignPartidaData();
    },
    updated() {
        let echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true
        })

        // echo.channel('join.game.'+this.partida.idPartida).listen('JoinGame',(data)=>{
        //     console.log(data)
        // });

        echo.join('join.game.'+this.partida.idPartida).here((users) => {
            console.log('test')
            console.log(users);
        }).listen('JoinGame',(data)=>{
            console.log(data)
        });
    },
    methods: {
        // joinGame(){
        //     this.$axios.post('/api/joingame', {
        //         idPartida: this.idPartida,
        //     }).then(response => {
        //     });
        // },
        assignPartidaData(){
            this.$axios.post('/api/getgamedata', {
                idPartida: this.idPartida,
            }).then(response => {
                this.partida = response.data;
                console.log(this.partida)
                this.cargandoDatos = false;
            });
        }
    }
}
</script>
