<template>
    <div class="container">
        <div class="bg-image1">
            <div class="text-center mt-lg-2">
                <a href="Home.vue"><img class="logo" src="../../images/logo.png"></a>
            </div>
            <div class="divReturn">
                <button class="d-inline return buttonClose" @click="$router.push('/games')" style="width: 175px;">Lista de partidas</button>
                <h1 class="text-1 title-profile">Crear Partida</h1>
            </div>
            <div class="mx-auto mt-lg-5 text-center" style="width: 65%; padding-bottom: 6rem;">
                <div class="d-inline-block me-lg-5">
                    <img class="" src="../../images/espia_fondo.png" style="width: 250px;">
                </div>
                <div class="text-center d-inline-block ms-lg-5" style="float: right;">
                    <form>
                        <div class="d-block my-lg-5">
                            <label for="type" class="text-1 mx-lg-4 fs-4">Tipo</label>
                            <select class="styleInput mx-lg-4" name="type" id="type" v-model="type" style="width: 140px;">
                                <option value="" disabled>-</option>
                                <option value="public">Pública</option>
                                <option value="private">Privada</option>
                            </select>
                        </div>
                        <div class="d-block my-lg-5">                    
                            <label for="type" class="text-1 mx-lg-4 fs-4">Victorias por partida</label>
                            <input class="styleInput text-center mx-lg-3" id="numMaxWins" type="number" v-model="numMaxWins" required autofocus autocomplete="off" style="width: 80px;">
                        </div>                    
                        <div class="mt-lg-4">
                            <button type="submit" class="return mx-lg-4" @click="newGame" style="width: 175px;">Crear partida</button>
                        </div>
                    </form>
                </div>                
            </div>

            <!-- <form>
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
            </form> -->
        </div>
    </div>
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
