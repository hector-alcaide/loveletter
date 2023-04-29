<template>
    <div v-if="cargandoDatos" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="partida">
        <h2>Jugar partida</h2>

        <h3>Test Jugadores</h3>
        <div v-for="item in partida.jugadores">
            <p :id="item.idJugador" :class="item.alias">{{ item.alias }}</p>
            <button class="mx-auto" v-if="cartaSobreJugador" @click="playCard(cartaJugada, item.idJugador)">
                Elegir este jugador
            </button>
        </div>

        <h3>Turno de {{partida.jugadores[partida.numJugadorTurno].alias}} </h3>
        <div  v-for="carta in partida.jugadores[idUsuario].mano">
            <p> {{ carta.titulo }} </p>
            <button class="mx-auto" v-if="permitirJugarCarta" @click="escogerCarta(carta.idCarta)">
                Jugar carta
            </button>
        </div>
        <button class="mx-auto" v-if="permitirRobar" @click="robarCarta()">
            Robar carta
        </button>


        <!--        <div v-if="this.partida.numJugadorTurno == this.partida.jugadores[this.idUsuario].numJugador">-->
        <!--            <button class="mx-auto" @click="robarCarta(this.idPartida)">-->
        <!--                Robar carta-->
        <!--            </button>-->
        <!--        </div>-->
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
            permitirRobar: false,
            permitirJugarCarta: false,
            cartaSobreJugador: false,
            cartaJugada: null,
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
            });

        // this.echo.join('play.game.'+this.idPartida+'player.'+this.idUsuario)
        //     .listen('PrivateActionUser',(data)=>{
        //         console.log(data);
        //     });

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
                this.permitirRobar = true;
            }else{
                console.log('otro jugador juega el turno')
            }
        },
        robarCarta(){
            this.permitirRobar = false;

            this.$axios.post('/api/stealcard', {
                partida: this.partida,
                idUsuario: this.idUsuario
            }).then(response => {
               // this.partida = response.data;
                console.log(response.data)
                this.permitirJugarCarta = true;
            });
        },
        escogerCarta(idCarta){
            // console.log(idCarta)
            this.permitirRobar = false;
            this.permitirJugarCarta = false;
            this.cartaJugada = idCarta;

            //TODO: solo tener un espia, mostrar mensaje se descarta el espia

            const tipoJugada = {
                'Espía': 'directa',
                'Guardia': 'sobreJugador',
                'Sacerdote': 'sobreJugador',
                'Barón': 'sobreJugador',
                'Doncella': 'directa',
                'Príncipe': 'sobreJugador',
                'Canciller': 'sobreMazo',
                'Rey': 'sobreJugador',
                'Condesa': 'directa',
                'Princesa': 'directa',
            };

            const carta = this.partida.referenciaMazo[idCarta];

            if(tipoJugada[carta.titulo] == 'directa'){
                this.playCard(idCarta);
            }else if(tipoJugada[carta.titulo] == 'sobreJugador'){
                this.cartaSobreJugador = true;
            };

        },
        playCard(idCarta, idRival = null){
            this.cartaSobreJugador = false;

            const arrayHand = Object.values(this.partida.jugadores[this.idUsuario].mano);
            const cardKeyDelete = arrayHand.findIndex(card => card.idCarta === idCarta);

            delete this.partida.jugadores[this.idUsuario].mano[cardKeyDelete];

            this.$axios.post('/api/playcard', {
                partida: this.partida,
                idJugador: this.idUsuario,
                idCarta: idCarta,
                idRival: idRival,
            }).then(response => {
                console.log(response)
            });
        },
        refreshPartidaData(){
            this.$axios.post('/api/getgamedata', {
                idPartida: this.idPartida,
            }).then(response => {
                this.partida = JSON.parse(response.data.partida);
                console.log(this.partida)
            });
        }
    }
}
</script>
