<template>
    <div v-if="loadingData" class="pantalla-carga">
        <span><p>Cargando...</p></span>
    </div>
    <div v-if="game">
        <div class="modal fade" id="showCardsToGuess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showCardsToGuess" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Escoge una carta</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="selectCardToGuess()">
                        <div class="modal-body">
                            <select id="cardToGuess" v-model="levelCardToGuess">
                                <option :value="card.level" v-for="card in cardsToGuess">{{card.title}}</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="mx-auto">
                                Escoger
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container-cards">
            <div class="container-cards-row-1">
                <div v-if="players[2]" id="div-player-3" class="div-player-3 text-center">
                    <div>
                        <label class="text-2">{{players[2].alias}}</label>
                    </div>
                    <img id="card3-down" class="" src="../../images/back-card.jpg" style="width: 90px" @click="rotateCard">
                    <img id="card3-up" class="" src="../../images/cards/card2.jpg" style="width: 90px; display: none;" @click="rotateCard">
                    <div class="div-extras-down">
                        <div id="protection-3" style="display: none">
                            <img class="" src="../../images/protection.png">
                        </div>
                        <div id="spy-3" style="display: none">
                            <img class="" src="../../images/spy.png">
                        </div>
                    </div>
                    <button class="mx-auto" v-if="typesCardResolution['onRival'] || typesCardResolution['onPlayer']" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[2].idPlayer, setFalseOnTypeRes: true})">
                        Elegir este jugador
                    </button>
                </div>
                <div v-if="players[1]" id="div-player-2" class="div-player-2 text-center">
                    <div>
                        <label class="text-2">{{players[1].alias}}</label>
                    </div>
                    <img src="../../images/back-card.jpg" style="width: 90px">
                    <div class="div-extras-down">
                        <div id="protection-2" style="display: none">
                            <img class="" src="../../images/protection.png">
                        </div>
                        <div id="spy-2" style="display: none">
                            <img class="" src="../../images/spy.png">
                        </div>
                    </div>
                    <button class="mx-auto" v-if="typesCardResolution['onRival'] || typesCardResolution['onPlayer']" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[1].idPlayer, setFalseOnTypeRes: true})">
                        Elegir este jugador
                    </button>
                    <button class="mx-auto" type="button" data-bs-toggle="modal" data-bs-target="#showCardsToGuess" v-if="typesCardResolution['onRivalOnCard']" @click="this.idRival_GuessCard = players[1].idPlayer">
                        Elegir este jugador
                    </button>
                </div>
                <div v-if="players[3]" id="div-player-4" class="div-player-4 text-center">
                    <div>
                        <label class="text-2">{{players[3].alias}}</label>
                    </div>
                    <img src="../../images/back-card.jpg" style="width: 90px">
                    <div class="div-extras-down">
                        <div id="protection-4" style="display: none">
                            <img class="" src="../../images/protection.png">
                        </div>
                        <div id="spy-4" style="display: none">
                            <img class="" src="../../images/spy.png">
                        </div>
                    </div>
                    <button class="mx-auto" v-if="typesCardResolution['onRival'] || typesCardResolution['onPlayer']" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[3].idPlayer, setFalseOnTypeRes: true})">
                        Elegir este jugador
                    </button>
                </div>
            </div>
            <div class="container-cards-row-2">
                <div id="tiradas" class="thrown-cards">
                    <!-- <img src="../../images/card1.jpg" style="width: 90px"> -->
                </div>
                <div class="mallet-cards">
                    <img src="../../images/mallet_5.png" style="width: 90px">
                    <label class="text-1 fs-4">{{ game.deck.length }}</label>
                    <button class="mx-auto" v-if="allowSteal" @click="stealCard()">
                        Robar carta
                    </button>
                </div>
            </div>
            <div class="container-cards-row-3">
                <div v-if="players[4]" id="div-player-5" class="div-player-5 text-center">
                    <div class="div-extras-up">
                        <div id="protection-5" style="display: none">
                            <img class="" src="../../images/protection.png">
                        </div>
                        <div id="spy-5" style="display: none">
                            <img class="" src="../../images/spy.png">
                        </div>
                    </div>
                    <img src="../../images/back-card.jpg" style="width: 90px">
                    <div>
                        <label class="text-2">{{players[4].alias}}</label>
                    </div>
                    <button class="mx-auto" v-if="typesCardResolution['onRival'] || typesCardResolution['onPlayer']" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[4].idPlayer, setFalseOnTypeRes: true})">
                        Elegir este jugador
                    </button>
                </div>
                <div v-if="players[0]" id="div-player-1" class="div-player-1 text-center">
                    <div>
                        <div id="protection-1" style="display: none">
                            <img class="" src="../../images/protection.png">
                        </div>
                        <div id="spy-1" style="display: none">
                            <img class="" src="../../images/spy.png">
                        </div>
                    </div>
                    <span v-if="hand">
                        <span v-for="idCard in hand">

                        <p>{{idCard}}</p>
                        <p>{{game.deckReference[idCard]}}</p>
                            <img class="mx-2" :src="game.deckReference[idCard].image" style="width: 90px">
                            <button class="mx-auto" v-if="allowPlayCard" @click="checkTypeCardResolve(idCard)">
                                Jugar carta
                            </button>
                        </span>
                    </span>
                    <div>
                        <label class="text-2">{{players[0].alias}}</label>
                    </div>
                    <button class="mx-auto" v-if="typesCardResolution['onPlayer']" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[5].idPlayer, setFalseOnTypeRes: true})">
                        Elegir este jugador
                    </button>
                </div>
                <div v-if="players[5]" id="div-player-6" class="div-player-6 text-center">
                    <div class="div-extras-up">
                        <div id="protection-6" style="display: none">
                            <img class="" src="../../images/protection.png">
                        </div>
                        <div id="spy-6" style="display: none">
                            <img class="" src="../../images/spy.png">
                        </div>
                    </div>
                    <img src="../../images/back-card.jpg" style="width: 90px">
                    <div>
                        <label class="text-2">{{players[5].alias}}</label>
                    </div>
                    <button class="mx-auto" v-if="typesCardResolution['onRival'] || typesCardResolution['onPlayer']" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[5].idPlayer, setFalseOnTypeRes: true})">
                        Elegir este jugador
                    </button>
                </div>
            </div>
        </div>
        <div class="container-frame">
            <div>
                <label class="text-2 fs-4 mt-lg-3">Turno de David</label>
            </div>
            <div>
                <label class="text-2 fs-4">Última tirada:</label>
                <label class="text-2">Jake ha usado barón. Ha comparado carta con Hector y ha ganado. Hector ha pedido con Príncipe</label>
            </div>
            <div>
                <label class="text-2 d-block fs-4">Puntos:</label>
                <label class="text-2 d-block fs-5">Jake 2</label>
                <label class="text-2 d-block fs-5">Hector 2</label>
                <label class="text-2 d-block fs-5">David 1</label>
            </div>
            <div>
                <label class="text-2 fs-4">Puntos para victoria: 5</label>
            </div>
            <div>
                <button class="exit-board" @click="$router.push('/home')">Salir</button>
            </div>
        </div>
        <button class="mx-auto" v-if="allowSteal" @click="stealCard()">
            Robar carta
        </button>

            <div v-if="this.game.turnPlayerNum == this.game.players[this.idUser].playerNum">
                   <button class="mx-auto" @click="stealCard(this.idGame)">
                       Robar card
                   </button>
            </div>

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
            players: [],
            hand: null,
            users: [],
            allowSteal: false,
            allowPlayCard: false,
            // cardOnPlayer: false,
            playedCard: null,
            typesCardResolution : {
                'direct': 'default',
                'onPlayer': false,
                'onRival': false,
                'onRivalOnCard': false,
            },
            cardsResolution: {
                '0': 'direct',
                '1': 'onRivalOnCard',
                '2': 'onRival',
                '3': 'onRival',
                '4': 'direct',
                '5': 'onPlayer',
                '6': 'direct',
                '7': 'onRival',
                '8': 'direct',
                '9': 'direct',
            },
            cardsToGuess: [],
            idRival_GuessCard: null,
            levelCardToGuess: null,
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
                // console.log(this.users)
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
                    this.hand = this.game.players[this.idUser].hand;

                    let arrayPositions = [4,2,1,3,5];
                    let playersLength = Object.keys(this.game.players).length;

                    this.players[0] = this.game.players[this.idUser];
                    let playerNum = this.game.players[this.idUser].playerNum;

                    let i;
                    let a = 1;
                    let b = 0;
                    let d = 0;
                    let z = 0;
                    for (let i = 0; i < (playersLength - 1); i++) {
                        b = playerNum + a;
                        if (b > playersLength) {
                            d++;
                            b = d;
                        }
                        for(let j = z; j < arrayPositions.length; j++){
                            if (playersLength -1 >= arrayPositions[j]) {
                                let players = Object.values(this.game.players);
                                let player = players.findIndex(pl => pl.playerNum === b);
                                this.players[arrayPositions[j]] = players[player];
                                z = j + 1;
                                break;
                            }
                        }
                    a++;
                    }

                    this.cardsToGuess = [
                        {level: '0', title: 'Espía'},
                        {level: '2', title: 'Sacerdote'},
                        {level: '3', title: 'Barón'},
                        {level: '4', title: 'Doncella'},
                        {level: '5', title: 'Príncipe'},
                        {level: '6', title: 'Canciller'},
                        {level: '7', title: 'Rey'},
                        {level: '8', title: 'Condesa'},
                        {level: '9', title: 'Princesa'},
                    ];

                    this.loadingData = false;

                    resolve();
                }).catch(error => {
                    reject(error);
                });
            });
        },
        deleteUserConnected(idUser){
            let array_pos = this.users.map(item => item.idUser).indexOf(idUser);
            this.users.splice(array_pos, 1);
        },
        playTurn(){
            let playerTurn = this.game.turnPlayerNum;
            let playerNum = this.game.players[this.idUser].playerNum;

            this.allowSteal = playerTurn != playerNum || this.allowPlayCard === true ? false : true;
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
        resolvePlayedCard({idCard, idRival = null, levelCardToGuess = null, setFalseOnTypeRes = false}){
            setFalseOnTypeRes === true ? this.typesCardResolution[this.cardsResolution[this.playedCard.level]] = false : '';

            const arrayHand = Object.values(this.game.players[this.idUser].hand);
            console.log(arrayHand)
            const cardKeyDelete = arrayHand.indexOf(idCard);
            console.log(cardKeyDelete)

            this.game.players[this.idUser].hand.splice(cardKeyDelete, 1);
            console.log( this.game.players[this.idUser].hand);

            this.$axios.post('/api/playcard', {
                game: this.game,
                idPlayer: this.idUser,
                idCard: idCard,
                idRival: idRival,
                levelCardToGuess: levelCardToGuess,
            }).then(response => {
                console.log(response)
            }).catch(e => {
                console.log(e)
            });
        },
        resolveChancellor({idCard}){
            const arrayHand = Object.values(this.game.players[this.idUser].hand);
            const cardKeyDelete = arrayHand.findIndex(idCard => idCard === idCard);

            delete this.game.players[this.idUser].hand[cardKeyDelete];

            this.$axios.post('/api/resolvechancellor', {
                game: this.game,
                idPlayer: this.idUser,
                idCard: idCard,
            }).then(response => {
                console.log(response)
            });
        },
        selectCardToGuess(){
            if (this.levelCardToGuess){
                this.resolvePlayedCard({idCard: this.playedCard.idCard, idRival: this.idRival_GuessCard, levelCardToGuess: this.levelCardToGuess, setFalseOnTypeRes: true});
            }else{
                console.log('escoger carta')
            }}
    }
}
</script>
