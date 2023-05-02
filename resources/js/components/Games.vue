<template>
    <h2>Partidas </h2>
    <button class="button_jugar mx-auto mt-lg-5" @click="$router.push('/games/create')">Crear game</button>

    <div class="row">
        <div class="col-3 mb-5" v-for="item in listGames">
            <p>Partida numero {{item.idGame}}</p>
            <button type="submit" class="mx-auto" @click="$router.push('/games/join/'+item.idGame)">Unirse a game</button>
        </div>
    </div>

    <!--    <form>-->
<!--        <div class="row">-->
<!--            <div class="col-md-3">-->
<!--                <label for="type" class="col-sm-4 col-form-label text-md-right">Tipo</label>-->
<!--                <select name="type" id="type" v-model="type">-->
<!--                    <option value="" disabled>-</option>-->
<!--                    <option value="public">Pública</option>-->
<!--                    <option value="private">Privada</option>-->
<!--                </select>-->
<!--            </div>-->
<!--            <div class="col-md-3">-->
<!--                <label for="type" class="col-sm-4 col-form-label text-md-right">Victorias por game</label>-->
<!--                <input id="numMaxWins" type="number" v-model="numMaxWins" required autofocus autocomplete="off">-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="row">-->
<!--            <div class="col-md-3">-->
<!--                <button type="submit" @click="newGame">Crear game</button>-->
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
        // echo.channel('game.'+ this.game_id).listen('CreateGame',(e)=>{
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
        }
    }
}
</script>
