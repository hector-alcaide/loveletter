<template>
    <h2>Partidas </h2>
    <button class="button_jugar mx-auto mt-lg-5" @click="$router.push('/games/create')">Crear partida</button>

    <div class="row">
        <div class="col-3 mb-5" v-for="item in listGames">
            <form @submit.prevent="joinGame(item.idPartida)">
                <p>Partida numero {{item.idPartida}}</p>
                <button type="submit" class="mx-auto">Unirse a partida</button>
            </form>
        </div>
    </div>

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
            //SALA PREVIA A PARTIDA COMENZADA
            listGames: [],
        }
    },
    mounted() {
        this.getGamesList();
        let echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true
        })

        echo.channel('games.list').listen('CreateGame',(data)=>{
            this.listGames.push(data);
            console.log(this.listGames)
        });

        // this.connectChannel_ListGames();
        // echo.channel('game.'+ this.partida_id).listen('CreateGame',(e)=>{
        //     console.log('go GameAction');
        //     //code for displaying the serve data
        //     console.log(e); // the data from the server
        // });
    },
    methods: {
        getGamesList(){
            this.$axios.post('api/getgameslist')
                .then(response => {
                    this.listGames = response.data;
                    console.log(this.listGames);
                }
            );
        },
        joinGame(idPartida){
            console.log('unido a partida' + idPartida)
        }
    }
}
</script>
