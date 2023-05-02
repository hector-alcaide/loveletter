<template>
    <div v-if="loadingData" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="game">
        <h2>Jugar partida</h2>

        <h3>Test Jugadores</h3>
        <div v-for="item in game.players">
            <p :id="item.idPlayer" :class="item.alias">{{ item.alias }}</p>
            <button class="mx-auto" v-if="typesCardResolution['onPlayer'] && item.idPlayer != idUser" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: item.idPlayer, setFalseOnTypeRes: true})">
                Elegir este jugador
            </button>
        </div>

        <h3>Turno de {{game.players[game.turnPlayerNum].alias}} </h3>
        <div  v-for="idCard in game.players[idUser].hand">
            <p> {{ game.deckReference[idCard].title }} </p>
            <button class="mx-auto" v-if="allowPlayCard" @click="checkTypeCardResolve(idCard)">
                Jugar carta
            </button>
        </div>
        <button class="mx-auto" v-if="allowSteal" @click="stealCard()">
            Robar carta
        </button>

        <!--        <div v-if="this.game.turnPlayerNum == this.game.players[this.idUser].playerNum">-->
        <!--            <button class="mx-auto" @click="stealCard(this.idGame)">-->
        <!--                Robar card-->
        <!--            </button>-->
        <!--        </div>-->
    </div>
</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        return {
            idGame: this.$route.params.idGame,
            loadingData: true,
            idUser: window.Laravel.user.idUser,
            game: null,
            users: [],
            allowSteal: false,
            allowPlayCard: false,
            // cardOnPlayer: false,
            playedCard: null,
            typesCardResolution : {
                'direct': 'default',
                'onPlayer': false,
                'onPlayerOnCard': false,
                'onDeck': false,
            },
            cardsResolution: {
                '0': 'direct',
                '1': 'onPlayerOnCard',
                '2': 'onPlayer',
                '3': 'onPlayer',
                '4': 'direct',
                '5': 'onPlayer',
                '6': 'onDeck',
                '7': 'onPlayer',
                '8': 'direct',
                '9': 'direct',
            },
            echo: new Echo({
                broadcaster: 'pusher',
                key: 'local',
                cluster: 'mt1',
                wsHost: '127.0.0.1',
                wsPort: 6001,
                forceTLS: false,
                disableStats: true
            }),
        }
    },
    beforeMount(){
        this.assignGameData();
    },
    mounted() {
        this.echo.join('play.game.'+this.idGame)
            .here((users) => {
                this.users = users;
                if(Object.keys(this.game.players).length == this.users.length){
                    console.log('empezar game')
                    this.playTurn();
                }else{
                    console.log('faltan players por unirse')
                }
            })
            .joining((user) => {
                this.users.push(user);
                console.log(this.users)
                if(Object.keys(this.game.players).length == this.users.length){
                    console.log('empezar game')
                    this.playTurn();
                }else{
                    console.log('faltan players por unirse')
                }
            })
            .leaving((user) => {
                this.deleteUserConnected(user.idUser);
                if(Object.keys(this.game.players).length == this.users.length){
                    console.log('empezar game')
                    this.playTurn();
                }
            })
            .listen('PublicActionUser',(data)=>{
                console.log(data);
                this.assignGameData().then(() => {
                    this.playTurn();
                });
            });

        // this.echo.join('play.game.'+this.idGame+'player.'+this.idUser)
        //     .listen('PrivateActionUser',(data)=>{
        //         console.log(data);
        //     });

    },
    beforeUnmount(){
        this.echo.leave('play.game.'+this.idGame);
    },
    methods: {
        assignGameData(){
            return new Promise((resolve, reject) => {
                this.$axios.post('/api/getgamedata', {
                    idGame: this.idGame,
                }).then(response => {
                    if(response.data.started == 0 || !response.data.players.some(el => el.idUser === this.idUser) ) {
                        window.location.href = "/games";
                    }

                    this.game = JSON.parse(response.data.game);
                    console.log(this.game);

                    this.loadingData = false;

                    resolve();
                }).catch(error => {
                    reject(error);
                });
            });
        },
        // assignGameData(){
        //     this.$axios.post('/api/getgamedata', {
        //         idGame: this.idGame,
        //     }).then(response => {
        //         if(response.data.started == 0 || !response.data.players.some(el => el.idUser === this.idUser) ) {
        //             window.location.href = "/games";
        //         }
        //
        //         this.game = JSON.parse(response.data.game);
        //         console.log(this.game);
        //
        //         this.loadingData = false;
        //     });
        // },
        deleteUserConnected(idUser){
            let array_pos = this.users.map(item => item.idUser).indexOf(idUser);
            this.users.splice(array_pos, 1);
        },
        playTurn(){
            let playerTurno = this.game.turnPlayerNum;
            let playerNum = this.game.players[this.idUser].playerNum;

            this.allowSteal = playerTurno != playerNum || this.allowPlayCard === true ? false : true;
        },
        stealCard(){
            this.allowSteal = false;

            this.$axios.post('/api/stealcard', {
                game: this.game,
                idUser: this.idUser
            }).then(response => {
               // this.game = response.data;
                console.log(response.data)
                this.allowPlayCard = true;
            });
        },
        checkTypeCardResolve: function (idCard) {
            //TODO: solo tiene un espia, mostrar mensaje se descarta el espia
            this.allowPlayCard = false;

            this.playedCard = this.game.deckReference[idCard];

            const cardResolution = this.cardsResolution[this.playedCard.level];

            if (this.typesCardResolution[cardResolution] === 'default') {
                this.resolvePlayedCard({idCard: idCard});
            } else {
                this.typesCardResolution[cardResolution] = true;
            }
        },
        resolvePlayedCard({idCard, idRival = null, idCardToGuess = null, setFalseOnTypeRes = false}){
            setFalseOnTypeRes === true ? this.typesCardResolution[this.cardsResolution[this.playedCard.level]] = false : '';

            const arrayHand = Object.values(this.game.players[this.idUser].hand);
            const cardKeyDelete = arrayHand.findIndex(card => card.idCard === idCard);

            delete this.game.players[this.idUser].hand[cardKeyDelete];

            this.$axios.post('/api/playcard', {
                game: this.game,
                idPlayer: this.idUser,
                idCard: idCard,
                idRival: idRival,
                idCardToGuess: idCardToGuess,
            }).then(response => {
                console.log(response)
            });
        },
    }
}
</script>
