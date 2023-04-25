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

        <h3>El {{jugadorTurno}} juega el turno</h3>
    </div>








</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        return {
            idPartida: this.$route.params.idPartida,
            cargandoDatos: true,
            idUsuario: window.Laravel.user.idUsuario,
            // partida: null,
            partida: null,
            usuarios: [],
            jugadorTurno: null,
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
        // this.getSessionPartida();
        // this.assignPartidaData();
    },
    mounted() {
        this.echo.join('play.game.'+this.idPartida)
            .here((users) => {

                this.getSessionPartida().then(response =>{
                    this.usuarios = users;
                    console.log(response)
                    if(this.partida.jugadores.length == this.usuarios.length){
                        console.log('empezar partida')
                        console.log(this.partida)
                        // this.jugarTurno(1);
                    }else{
                        console.log('faltan jugadores por unirse')
                    }
                });
            })
            .joining((user) => {
                this.usuarios.push(user);
                if(this.partida.jugadores.length == this.usuarios.length){
                    console.log('empezar partida')
                }else{
                    console.log('faltan jugadores por unirse')
                }
            })
            .leaving((user) => {
                this.deleteUserConnected(user.idUsuario);
            })
            .listen('Action',(data)=>{
                console.log(data)
                window.location.href = "/games/play/"+data.idPartida;
            });
    },
    beforeUnmount(){
        this.echo.leave('play.game.'+this.idPartida);
    },
    methods: {
        // assignPartidaData(){
        //     this.$axios.post('/api/getgamedata', {
        //         idPartida: this.idPartida,
        //     }).then(response => {
        //         if (response.data.empezada == 1 && response.data.jugadores.some(el => el.idUsuario === this.idUsuario) ){
        //             this.partida = response.data;
        //             this.cargandoDatos = false;
        //             console.log(this.partida)
        //         }else{
        //             window.location.href = "/games";
        //         }
        //     });
        // },
        getSessionPartida(){
            return this.$axios.post('/api/getgamesession', {
            }).then(response => {
                this.partida = response.data;
            });
        },
        getPartidaData(){
            this.$axios.post('/api/getgamedata', {
                idPartida: this.idPartida,
            }).then(response => {
                if (response.data.empezada == 1){
                    window.location.href = "/games";
                }else{
                    this.partida = response.data;
                    console.log(this.partida)
                }
            });
        },
        jugarTurno(idJugadorTurno){


            this.jugadorTurno == this.partida.jugadores['idJugadorTurno'];

            if(idJugadorTurno == this.idUsuario){
                console.log()
            }else{

            }
        }
    }
}
</script>
