<template>
    <div v-if="this.cargandoDatos" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="this.partida">
        <h2>Jugar partida</h2>

        <h3>Test Jugadores</h3>
        <div v-for="item in usuarios" :key="item.id">
            <p>{{ item.alias }}</p>
        </div>
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
                this.usuarios = users.sort((a,b) => new Date(a.conexion_canal) - new Date(b.conexion_canal));
                console.log('usuarios conectados:')
                console.log(this.usuarios)
            })
            .joining((user) => {
                this.usuarios.push(user);
                console.log('subido usuario, nuevo array:')
                console.log(this.usuarios)
            })
            .leaving((user) => {
                this.deleteUserConnected(user.idUsuario);
                console.log('usuario eliminado, nuevo array:')
                console.log(this.usuarios)
            })
    },
    beforeUnmount(){
        this.echo.leave('play.game.'+this.idPartida);
    },
    methods: {
        assignPartidaData(){
            this.$axios.post('/api/getgamedata', {
                idPartida: this.idPartida,
            }).then(response => {
                this.partida = response.data;
                console.log(this.partida)
                this.cargandoDatos = false;
            });
        },
        deleteUserConnected(idUsuario){
            let array_pos = this.usuarios.map(item => item.idUsuario).indexOf(idUsuario);
            this.usuarios.splice(array_pos, 1);
        },
    }
}
</script>
