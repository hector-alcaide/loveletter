<template>
    <h2>Partidas </h2>
    <button class="button_jugar mx-auto mt-lg-5" @click="$router.push('/games/create')">Crear partida</button>

    <!--    <form>-->
<!--        <div class="row">-->
<!--            <div class="col-md-3">-->
<!--                <label for="tipo" class="col-sm-4 col-form-label text-md-right">Tipo</label>-->
<!--                <select name="tipo" id="tipo" v-model="tipo">-->
<!--                    <option value="" disabled>-</option>-->
<!--                    <option value="publica">Pública</option>-->
<!--                    <option value="privada">Privada</option>-->
<!--                </select>-->
<!--            </div>-->
<!--            <div class="col-md-3">-->
<!--                <label for="tipo" class="col-sm-4 col-form-label text-md-right">Victorias por partida</label>-->
<!--                <input id="numeroVictoriasMaximas" type="number" v-model="numeroVictoriasMaximas" required autofocus autocomplete="off">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-md-3">-->
<!--                <button type="submit" @click="newGame">Crear partida</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </form>-->
<!--    <form>-->
<!--        <button type="submit" @click="newGame">Generar mazo</button>-->
<!--    </form>-->
</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        return {
            //CREAR PARTIDA
            tipo: "publica",
            numeroVictoriasMaximas: 3,
            //SALA PREVIA A PARTIDA COMENZADA
            partida_id: 4,
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

        echo.channel('games.list').listen('CreateGame',(e)=>{
            console.log('Nueva partida creada');
            console.log(e);
        });

        // this.connectChannel_ListGames();
        // echo.channel('game.'+ this.partida_id).listen('CreateGame',(e)=>{
        //     console.log('go GameAction');
        //     //code for displaying the serve data
        //     console.log(e); // the data from the server
        // });
    },
    methods: {
        // connectChannel_ListGames(){
        //     this.$axios.post('channels/games.list', {
        //
        //     });
        //     console.log('hola')
        // }
    }
}
</script>
