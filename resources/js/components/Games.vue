<template>
    <div class="bg-image1">
        <div class="text-center mt-lg-2">
            <a href="Home.vue"><img class="logo" src="../../images/logo.png"></a>
        </div>
        <div class="divReturn">
            <button class="d-inline return buttonClose" @click="$router.push('/home')">Volver</button>
            <h1 class="text-1 title-profile">Partidas</h1>
        </div>
        <div class="mx-5 text-center mx-auto" style="width: 85%">
            <button class="button_jugar mt-lg-5" @click="$router.push('/games/create')" style="margin-left: 315px">Crear partida</button>
        </div>
        <div class="text-center" style="width: 80%; margin-left: 100px; padding-bottom: 4rem;">
            <!-- <div class="text-center mb-5 d-inline-block" v-for="item in listGames" style="width: 250px">
                <label class="text-2 fs-4">Partida número {{item.idGame}}</label>
                <img class="my-2" src="../../images/imagen-partida-peq.jpg" style="width: 200px">
                <button type="submit" class="mx-auto" @click="$router.push('/games/join/'+item.idGame)">Unirse a partida</button>
            </div> -->
            <carousel :items-to-show="1">
                <slide v-for="item in listGames" :key="item.idGame">
                    <div class="text-center mb-5 d-inline-block mx-2" v-for="item in listGames" style="width: 250px">
                        <label class="text-2 fs-4">Partida número {{item.idGame}}</label>
                        <img class="my-2" src="../../images/imagen-partida-peq.jpg" style="width: 200px">
                        <button type="submit" class="mx-auto" @click="$router.push('/games/join/'+item.idGame)">Unirse a partida</button>
                    </div>
                </slide>

                <template #addons>
                <navigation />
                <pagination />
                </template>
            </carousel>
            
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
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default {
    name: 'App',
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
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
