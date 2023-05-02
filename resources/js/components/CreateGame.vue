<template>
    <button class="button_jugar mx-auto mt-lg-5" @click="$router.push('/games')">Lista games</button>

    <form>
        <div class="row">
            <div class="col-md-3">
                <label for="type" class="col-sm-4 col-form-label text-md-right">Tipo</label>
                <select name="type" id="type" v-model="type">
                    <option value="" disabled>-</option>
                    <option value="public">Pública</option>
                    <option value="private">Privada</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="type" class="col-sm-4 col-form-label text-md-right">Victorias por game</label>
                <input id="numMaxWins" type="number" v-model="numMaxWins" required autofocus autocomplete="off">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <button type="submit" @click="newGame">Crear game</button>
            </div>
        </div>
    </form>
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
            type: "public",
            numMaxWins: 3,
            //SALA PREVIA A PARTIDA COMENZADA
            game_id: '',
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
        // echo.channel('game.'+ this.game_id).listen('CreateGame',(e)=>{
        //     console.log('go GameAction');
        //     //code for displaying the serve data
        //     console.log(e); // the data from the server
        // });
    },
    methods: {
        newGame(e){
            e.preventDefault();
            this.$axios.post('/api/newgame', {
                type: this.type,
                numMaxWins: this.numMaxWins,
            }).then(response => {
                console.log(response)
                this.$router.push('/games/join/'+response.data.idGame);
            }).catch(function (error) {
                console.error(error);
            });
        }
    }
}
</script>
