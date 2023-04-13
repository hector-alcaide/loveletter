<template>
    <div class="text-center">
        <img class="logo" src="../../images/logo.png">
    </div>
    <div class="text-center">
        <div class="d-inline-block me-lg-5">
            <img class="guardia" src="../../images/guardia1.png">
        </div>
        <div class="d-inline-block align-middle text-center my-lg-5 mx-lg-5">
            <button class="button_jugar d-block mx-auto mt-lg-5">Jugar</button>
            <button class="button_menu d-block mx-auto mt-lg-5 mb -lg-4">Perfil</button>
            <button class="button_menu d-block mx-auto my-lg-4">Amigos</button>
            <button class="button_menu d-block mx-auto my-lg-4">Ranking</button>
        </div>
        <div class="d-inline-block ms-lg-5">
            <img class="guardia" src="../../images/guardia2.png">
        </div>
    </div>

    <button type="submit" class="btn btn-success" @click="SendM">
        Login
    </button>

    <div id="juego">
        <div class="player" v-for="(player, index) in players" :key="index">
            <h2>Jugador {{index + 1}}</h2>
            <div class="cards">
                <div v-for="(card, indexc) in player.cards" :key="indexc">
                    <div class="card"  v-bind:class="[playersCards[index].cards[indexc] ? 'active' : '']"  @click="clickCard(index,indexc)">
                        {{card}}
                        {{playersCards[index].cards[indexc]}}
                    </div>
                </div>
            </div>
        </div>
        <div class="deck">
            <h2>Mazo</h2>
            <div class="card"></div>
        </div>
    </div>
</template>

<script>
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
export default {
    name: "Home",
    data() {
        return {
            partida_id: 1,
            user_id: 1,
            players: [
                { cards: ['As Cor', 'ReyCor'] },
                { cards: ['As Diam', 'Rey Diam'] },
                { cards: ['As Trébo', 'Rey Trébo'] },
                { cards: ['As Picas', 'Rey Picas'] }
            ],
            playersCards: [
                { cards: [false, false] },
                { cards: [false, false] },
                { cards: [false, false] },
                { cards: [false, false] }
            ]
        }
    },
    created() {
    },
    mounted(){


    },
    methods: {
        SendM(e) {
            let echo = new Echo({
                broadcaster: 'pusher',
                key: "local",
                wsHost:"127.0.0.1",
                wsPort: 6001,
                cluster: "mt1",
                forceTLS: false,
                disableStats:true,
            });
            /*
            echo.channel('public').handleEvent('Hello',(e)=>{
                console.log('go public');
                //code for displaying the serve data
                console.log(e); // the data from the server
            });
               */
            echo.channel('game.'+ this.partida_id).listen('Action',(e)=>{
                console.log('go GameAvtion');
                //code for displaying the serve data
                console.log(e); // the data from the server
            });

            echo.channel('gameuser.'+ this.partida_id +'.' +  this.user_id).listen('ActionUser',(e)=>{
                console.log('go GameAvtionUser');
                //code for displaying the serve data

                console.log(e); // the data from the server
                console.log(e.action);
                let action = JSON.parse(e.action);
                console.log(action.carta);
                this.clickCard(action.user,action.carta);
            });
        },

        clickCard(index,indexc) {
            //e.target.classList.toggle('active');
            console.log(index);
            this.playersCards[index].cards[indexc] = !this.playersCards[index].cards[indexc];
            // this.$axios.get('/sanctum/csrf-cookie').then(response => {
            //     this.$axios.get('/api/posts')
            //         .then(response => {
            //             this.posts = response.data;
            //         });
            // });
        }
    }
}
</script>

<style scoped>
#juego {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100vh;
    padding: 20px;
    background-color: green;
}

.player {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 25%;
    height: 50%;
    border: 2px solid white;
    border-radius: 10px;
    padding: 20px;
    color: white;
    background-color: blue;
}

.player h2 {
    margin: 0 0 10px;
    font-size: 24px;
}

.cards {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
}

.card {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 80px;
    height: 120px;
    margin: 0 10px;
    font-size: 24px;
    font-weight: bold;
    color: black;
    background-color: yellow;
}

.deck {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 25%;
    height: 50%;
    border: 2px solid white;
    border-radius: 10px;
    padding: 20px;
    color: white;
    background-color: red;
}

.deck h2 {
    margin: 0 0 10px;
    font-size: 24px;
}

.deck .card {
    width: 120px;
    height: 180px;
    background-color: white;
    border-radius: 10px;
}

.active {
    transform: rotateY(180deg);
    transition: transform 0.5s ease;
}

</style>
