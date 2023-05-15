<template>
    <div class="board">
        <div v-if="loadingData" class="pantalla-carga">
            <span><p>Cargando...</p></span>
        </div>
        <div v-if="game">
            <div class="modal fade" id="showRivalCard" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showRivalCard" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0 pe-1">
                            <div class="modal-div-title">
                                <h2 class="modal-title" id="staticBackdropLabel" v-if="priestResponseObj.rival">Carta de {{priestResponseObj.rival.alias}}</h2>
                            </div>
                            <button type="button" class="mx-lg-3 close-modal" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <div class="modal-body pb-3">
                            <div class="row d-flex justify-content-center">
                                <div class="col-2 mx-2 mb-3 d-flex justify-content-center">
                                    <img class="rival-card" v-if="priestResponseObj.cardRival" :src="priestResponseObj.cardRival.image" :alt="priestResponseObj.cardRival.title">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pb-3 pt-1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="showCardsToGuess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showCardsToGuess" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0 pe-1">
                            <div class="modal-div-title">
                                <h2 class="modal-title" id="staticBackdropLabel">Escoge una carta</h2>
                            </div>
                            <button type="button" class="mx-lg-3 close-modal" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <form @submit.prevent="guessCard()">
                            <div class="modal-body pb-3">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-2 mx-2 mb-3 d-flex justify-content-center" v-for="card in cardsToGuess">
                                        <img class="card-guess" :src="card.image" :alt="card.title" @click="selectCardToGuess(card.level, $event)">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pb-4 pt-1">
                                <button class="mx-auto mb-2 modal-button">
                                    Escoger
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="container-cards">
                <div class="container-cards-row-1">
                    <h2 id="title-play" class="message-title-play fade">{{ titleMessage }}</h2>
                    <div v-if="players[2]" id="div-player-3" class="div-player-3 text-center">
                        <div>
                            <label class="text-2">{{players[2].alias}}</label>
                        </div>
                        <img v-if="players[2].activePlayer" id="card3-down" class="" src="../../images/back_card.webp" @click="rotateCard">
                        <div class="div-extras-down">
                            <div id="protection-3" v-if="players[2].maid === true">
                                <img class="" src="../../images/protection.svg" alt="Imagen protección" title="Este jugador está protegido">
                            </div>
                            <div id="spy-3" v-if="players[2].spy === true">
                                <img class="" src="../../images/spy.svg" alt="Icono espía" title="Este jugador tiene el espía">
                            </div>
                        </div>
                        <div v-if="players[2].activePlayer === true">
                            <button class="mx-auto" v-if="players[2].maid === false && (typesCardResolution['onRival'] || typesCardResolution['onPlayer'])" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[2].idPlayer, setFalseOnTypeRes: true})">
                                Escoger
                            </button>
                            <button class="mx-auto" type="button" data-bs-toggle="modal" data-bs-target="#showCardsToGuess" v-if="players[2].maid === false && typesCardResolution['onRivalOnCard']" @click="this.idRival_GuessCard = players[2].idPlayer">
                                Escoger
                            </button>
                        </div>
                    </div>
                    <div v-if="players[1]" id="div-player-2" class="div-player-2 text-center">
                        <div>
                            <label class="text-2">{{players[1].alias}}</label>
                        </div>
                        <img v-if="players[1].activePlayer == true" src="../../images/back_card.webp">
                        <div class="div-extras-down">
                            <div id="protection-2" v-if="players[1].maid === true">
                                <img class="" src="../../images/protection.svg" alt="Imagen protección" title="Este jugador está protegido">
                            </div>
                            <div id="spy-2" v-if="players[1].spy === true">
                                <img class="" src="../../images/spy.svg" alt="Icono espía" title="Este jugador tiene el espía">
                            </div>
                        </div>
                        <div v-if="players[1].activePlayer === true">
                            <button class="mx-auto" v-if="players[1].maid === false && (typesCardResolution['onRival'] || typesCardResolution['onPlayer'])" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[1].idPlayer, setFalseOnTypeRes: true})">
                                Escoger
                            </button>
                            <button class="mx-auto" type="button" data-bs-toggle="modal" data-bs-target="#showCardsToGuess" v-if="players[1].maid === false && typesCardResolution['onRivalOnCard']" @click="this.idRival_GuessCard = players[1].idPlayer">
                                Escoger
                            </button>
                        </div>
                    </div>
                    <div v-if="players[3]" id="div-player-4" class="div-player-4 text-center">
                        <div>
                            <label class="text-2">{{players[3].alias}}</label>
                        </div>
                        <img v-if="players[3].activePlayer" src="../../images/back_card.webp">
                        <div class="div-extras-down">
                            <div id="protection-4" v-if="players[3].maid === true">
                                <img class="" src="../../images/protection.svg" alt="Imagen protección" title="Este jugador está protegido">
                            </div>
                            <div id="spy-4" v-if="players[3].spy === true">
                                <img class="" src="../../images/spy.svg" alt="Icono espía" title="Este jugador tiene el espía">
                            </div>
                        </div>
                        <div v-if="players[3].activePlayer === true">
                            <button class="mx-auto" v-if="players[3].maid === false && (typesCardResolution['onRival'] || typesCardResolution['onPlayer'])" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[3].idPlayer, setFalseOnTypeRes: true})">
                                Escoger
                            </button>
                            <button class="mx-auto" type="button" data-bs-toggle="modal" data-bs-target="#showCardsToGuess" v-if="players[3].maid === false && typesCardResolution['onRivalOnCard']" @click="this.idRival_GuessCard = players[3].idPlayer">
                                Escoger
                            </button>
                        </div>
                    </div>
                </div>
                <div class="container-cards-row-2">
                    <div id="thrownCards" class="thrown-cards">
                    </div>
                    <div class="discard-card" v-if="allowDiscardCard">
                        <button class="mx-auto py-2" @click="discardCard(playedCard.idCard)">
                            Descartar carta
                        </button>
                    </div>
                    <div class="mallet-cards">
                        <img v-if="allowSteal" @click="stealCard()" class="deck-steal" :src="deckRouteImg" style="width: 90px">
                        <img id="deck" v-else :src="deckRouteImg" style="width: 90px">
                        <label class="text-1 fs-4" id="deck-length" @click="stealCard()">{{ game.deck.length }}</label>
                    </div>
                </div>
            <div class="container-cards-row-3">
                <div v-if="players[4]" id="div-player-5" class="div-player-5 text-center">
                    <div class="div-extras-up">
                        <div id="protection-5" v-if="players[4].maid === true">
                            <img class="" src="../../images/protection.svg" alt="Imagen protección" title="Este jugador está protegido">
                        </div>
                        <div id="spy-5" v-if="players[4].spy === true">
                            <img class="" src="../../images/spy.svg" alt="Icono espía" title="Este jugador tiene el espía">
                        </div>
                    </div>
                    <img v-if="players[4].activePlayer == true" src="../../images/back_card.webp">
                    <div :style="players[4].activePlayer === false ? { 'margin-top': '135px' } : ''">
                        <label class="text-2">{{players[4].alias}}</label>
                    </div>
                    <div v-if="players[4].activePlayer === true">
                        <button class="mx-auto" v-if="players[4].maid === false && (typesCardResolution['onRival'] || typesCardResolution['onPlayer'])" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[4].idPlayer, setFalseOnTypeRes: true})">
                            Escoger
                        </button>
                        <button class="mx-auto" type="button" data-bs-toggle="modal" data-bs-target="#showCardsToGuess" v-if="players[4].maid === false && typesCardResolution['onRivalOnCard']" @click="this.idRival_GuessCard = players[4].idPlayer">
                            Escoger
                        </button>
                    </div>
                </div>
                <div v-if="players[0]" id="div-player-1" class="div-player-1 text-center">
                    <div>
                        <div id="protection-1" v-if="players[0].maid === true">
                            <img class="" src="../../images/protection.svg" alt="Imagen protección" title="Este jugador está protegido">
                        </div>
                        <div id="spy-1" v-if="players[0].spy === true">
                            <img class="" src="../../images/spy.svg" alt="Icono espía" title="Este jugador tiene el espía">
                        </div>
                    </div>
                    <span v-for="idCard in hand" v-if="players[0].activePlayer == true" class="myCards" style="height: 250px;">
                        <img class="mx-2 myCards-play" v-if="allowPlayCard && !chooseCardToKeep && (!forceThrowCountess)" :src="game.deckReference[idCard].image" @click="checkTypeCardResolve(idCard)">
                        <img class="mx-2 myCards-play" v-else-if="allowPlayCard && !chooseCardToKeep && (forceThrowCountess && game.deckReference[idCard].level == 8)" :src="game.deckReference[idCard].image" @click="checkTypeCardResolve(idCard)">
                        <img class="mx-2" v-else-if="!allowPlayCard && !chooseCardToKeep || (forceThrowCountess && game.deckReference[idCard].level != 8)" :src="game.deckReference[idCard].image">
                        <img class="mx-2 myCards-play" v-if="chooseCardToKeep" :src="game.deckReference[idCard].image" @click="resolveChancellor({idCard: idCard})">
                    </span>
                    <div :style="players[0].activePlayer === false ? { 'margin-top': '135px' } : ''">
                        <label class="text-2">{{players[0].alias}}</label>
                    </div>
                    <button class="mx-auto" v-if="typesCardResolution['onPlayer']" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[0].idPlayer, setFalseOnTypeRes: true})">
                        Escoger
                    </button>
                </div>
                <div v-if="players[5]" id="div-player-6" class="div-player-6 text-center">
                    <div class="div-extras-up">
                        <div id="protection-6" v-if="players[5].maid === true">
                            <img class="" src="../../images/protection.svg" alt="Imagen protección" title="Este jugador está protegido">
                        </div>
                        <div id="spy-6" v-if="players[5].spy === true">
                            <img class="" src="../../images/spy.svg" alt="Icono espía" title="Este jugador tiene el espía">
                        </div>
                    </div>
                    <img v-if="players[5].activePlayer == true" src="../../images/back_card.webp">
                    <div :style="players[5].activePlayer === false ? { 'margin-top': '135px' } : ''">
                        <label class="text-2">{{players[5].alias}}</label>
                    </div>
                    <div v-if="players[5].activePlayer === true">
                        <button class="mx-auto" v-if="players[5].maid === false && (typesCardResolution['onRival'] || typesCardResolution['onPlayer'])" @click="resolvePlayedCard({idCard: playedCard.idCard, idRival: players[5].idPlayer, setFalseOnTypeRes: true})">
                            Escoger
                        </button>
                        <button class="mx-auto" type="button" data-bs-toggle="modal" data-bs-target="#showCardsToGuess" v-if="players[5].maid === false && typesCardResolution['onRivalOnCard']" @click="this.idRival_GuessCard = players[5].idPlayer">
                            Escoger
                        </button>
                    </div>
                </div>
            </div>
        </div>
            <div class="board_buttons">
                <button class="mx-lg-3" onclick="window.open('https://www.baobablibros.es/archivos/love-letter-reglas-del-juego.pdf')">?</button>
                <button class="mx-lg-3 end_board" @click="$router.push('/home')">X</button>
            </div>
            <div class="container-frame">
                <div>
                    <label class="text-2 fs-4 mt-lg-3">Turno de {{ turn }}</label>
                </div>
                <div>
                    <label class="text-2 fs-4">Última jugada:</label>
                    <label class="text-2">{{ message }}</label>
                </div>
                <div>
                    <label class="text-2 d-block fs-4">Puntos:</label>
                    <div v-for="item in game.players">
                        <label class="text-2 d-block fs-5">{{item.alias}}: {{ item.roundWins }}</label>
                    </div>
                </div>
                <div>
                    <label class="text-2 fs-4">Puntos para victoria: {{ this.game.numMaxWins }}</label>
                </div>
            </div>
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
            message: "",
            turn: "",
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
            chooseCardToKeep: false,
            deckRouteImg: false,
            forceThrowCountess: false,
            priestResponseObj: {
                'rival': null,
                'cardRival': null
            },
            titleMessage: null,
            allowDiscardCard: false,
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
    // beforeMount(){
    //     this.assignGameData();
    // },
    mounted() {
        this.assignGameData().then(() => {

            this.echo.join('play.game.'+this.idGame)
            .here((users) => {
                this.users = users;
                if(Object.keys(this.game.players).length == this.users.length){
                    this.playTurn();
                }
            })
            .joining((user) => {
                this.users.push(user);
                // console.log(this.users)
                if(Object.keys(this.game.players).length == this.users.length){
                    this.playTurn();
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

                if(data.newRound === true){

                    setTimeout(() => {
                        console.log("setTimeout");
                        this.assignGameData().then(() => {

                            let playerWinner = Object.values(this.game.players).filter(({roundWins}) => roundWins >= this.game.numMaxWins);
                            console.log(playerWinner);
                            if(playerWinner.length != 0){
                                //Finalizar la partida
                                console.log("Finalizar partida");
                                console.log(playerWinner[0].idPlayer);
                                this.game.passRound = 2;
                                let finalGame = this.endGame(playerWinner[0].idPlayer);
                                return false;
                            }

                            // data.changeTurn === false && this.playedCard.level == 6 ? this.chooseCardToKeep = true : this.playTurn();
                            this.message = data.message;

                            if (this.playedCard && this.playedCard.level == 6 && data.changeTurn === false){
                                this.chooseCardToKeep = true;
                                this.playedCard = false;
                            }
                            if(data.changeTurn === true){
                                this.playTurn();
                            }

                            if(this.allowPlayCard === true){
                                if(this.hand.some(idCard => idCard === 20)){
                                    let otherCard = this.hand.filter(idCard => idCard != 20);
                                    if(otherCard){
                                        let levelOtherCard = this.game.deckReference[otherCard].level;
                                        if(levelOtherCard == 5 || levelOtherCard == 7){
                                            this.forceThrowCountess = true;
                                        }
                                    }
                                }
                            }
                        });
                    }, 5000);
                }else{
                    console.log("else newRound");
                    this.assignGameData().then(() => {

                        // data.changeTurn === false && this.playedCard.level == 6 ? this.chooseCardToKeep = true : this.playTurn();
                        this.message = data.message;

                        if (this.playedCard && this.playedCard.level == 6 && data.changeTurn === false){
                            this.chooseCardToKeep = true;
                            this.playedCard = false;
                        }
                        if(data.changeTurn === true){
                            this.playTurn();
                        }

                        if(this.allowPlayCard === true){
                            if(this.hand.some(idCard => idCard === 20)){
                                let otherCard = this.hand.filter(idCard => idCard != 20);
                                if(otherCard){
                                    let levelOtherCard = this.game.deckReference[otherCard].level;
                                    if(levelOtherCard == 5 || levelOtherCard == 7){
                                        this.forceThrowCountess = true;
                                    }
                                }
                            }
                        }
                    });
                }
            });

        this.echo.join('play.game.'+this.idGame+'.player.'+this.idUser)
            .listen('PrivateActionUser',(data)=>{
                console.log("Mensaje privado");
                console.log(data);
                if(data.newRound == true){
                    console.log('nueva ronda. msg privado')
                    console.log(data.spy)
                    this.newRound(data.spy);
                }
            });
        });
    },
    beforeUnmount(){
        this.echo.leave('play.game.'+this.idGame);
        this.echo.leave('play.game.'+this.idGame+'.player.'+this.idUser);
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

                    if(this.game.deck.length >= 15){
                        this.deckRouteImg = "/images/mallet_5.svg";
                    }else if(this.game.deck.length < 15 && this.game.deck.length >= 12){
                        this.deckRouteImg = "/images/mallet_4.svg";
                    }else if(this.game.deck.length < 12 && this.game.deck.length >= 8){
                        this.deckRouteImg = "/images/mallet_3.svg";
                    }else if(this.game.deck.length < 8 && this.game.deck.length >= 4){
                        this.deckRouteImg = "/images/mallet_2.svg";
                    }else if(this.game.deck.length < 4 && this.game.deck.length >= 1){
                        this.deckRouteImg = "/images/mallet_1.svg";
                    }

                    //Posicionar a los jugadores en sus posiciones
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
                        {level: '0', title: 'Espía', image: '/images/cards/card0.jpg'},
                        {level: '2', title: 'Sacerdote', image: '/images/cards/card2.jpg'},
                        {level: '3', title: 'Barón', image: '/images/cards/card3.jpg'},
                        {level: '4', title: 'Doncella', image: '/images/cards/card4.jpg'},
                        {level: '5', title: 'Príncipe', image: '/images/cards/card5.jpg'},
                        {level: '6', title: 'Canciller', image: '/images/cards/card6.jpg'},
                        {level: '7', title: 'Rey', image: '/images/cards/card7.jpg'},
                        {level: '8', title: 'Condesa', image: '/images/cards/card8.jpg'},
                        {level: '9', title: 'Princesa', image: '/images/cards/card9.jpg'},
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

            //Poner las cartas lanzadas
            let random = Array.from({length: 5}, () => Math.floor(Math.random() * 5));

            let div = document.getElementById('thrownCards');
            div.innerHTML = '';
            let count = 0;
            this.game.thrownCards.forEach(res =>{
                div.innerHTML +=
                    `<img class="carta${random[count]}" src="/images/cards/card${this.game.deckReference[res].level}.jpg" style="width: 105px">`;
                count++;
                if(count == 5){
                    count = 0;
                }
            });

            let playerTurn = this.game.turnPlayerNum;
            let playerNum = this.game.players[this.idUser].playerNum;

            let turnName = Object.values(this.game.players).find(({playerNum}) => playerNum === this.game.turnPlayerNum)
            this.turn = turnName.alias;

            console.log("Mensage de paso de turno");
            console.log(this.game);
            if(this.game.passRound == 0){
                this.showTitleMessage('Turno de ' + this.turn);
            }else if(this.game.passRound == 1){
                this.showTitleMessage('Turno finalizado');
            }else if(this.game.passRound == 2){
                this.showTitleMessage('Partida finalizada');
            }

            this.allowSteal = playerTurn != playerNum || this.allowPlayCard === true ? false : true;

            const deck_length = document.getElementById('deck-length');
            this.allowSteal === true ? deck_length.classList.add('cursor-pointer') : deck_length.classList.remove('cursor-pointer');
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
            this.forceThrowCountess = false;

            this.playedCard = this.game.deckReference[idCard];

            const cardResolution = this.cardsResolution[this.playedCard.level];

            if (this.typesCardResolution[cardResolution] === 'default') {
                this.resolvePlayedCard({idCard: idCard});
            }else if(cardResolution === 'onPlayer'){
                this.typesCardResolution[cardResolution] = true;
            } else {
                //comprobar que si todos los jugadores estan protegidos permita descartar carta
                const numProtectedPlayers = Object.values(this.game.players).filter(p => p.maid).length;
                numProtectedPlayers === Object.values(this.game.players).length - 1 ? this.allowDiscardCard = true : this.typesCardResolution[cardResolution] = true;
            }
        },
        discardCard(idCard){
            this.allowDiscardCard = false;

            const arrayHand = Object.values(this.game.players[this.idUser].hand);
            const cardKeyDelete = arrayHand.indexOf(idCard);

            this.game.players[this.idUser].hand.splice(cardKeyDelete, 1);

            this.$axios.post('/api/discardCard', {
                game: this.game,
                idPlayer: this.idUser,
                idCard: idCard,
            }).then(response => {
                console.log(response)
            })
        },
        resolvePlayedCard({idCard, idRival = null, levelCardToGuess = null, setFalseOnTypeRes = false}){
            setFalseOnTypeRes === true ? this.typesCardResolution[this.cardsResolution[this.playedCard.level]] = false : '';

            const arrayHand = Object.values(this.game.players[this.idUser].hand);
            const cardKeyDelete = arrayHand.indexOf(idCard);

            this.game.players[this.idUser].hand.splice(cardKeyDelete, 1);

            this.$axios.post('/api/playcard', {
                game: this.game,
                idPlayer: this.idUser,
                idCard: idCard,
                idRival: idRival,
                levelCardToGuess: levelCardToGuess,
            }).then(response => {
                console.log(response)

                //priest
                if(this.game.deckReference[idCard].level == 2){
                    const rivalCard = this.game.players[idRival].hand[0];
                    this.priestResponseObj.rival = this.game.players[idRival];
                    this.priestResponseObj.cardRival = this.game.deckReference[rivalCard];

                    const myModal = new bootstrap.Modal(document.getElementById("showRivalCard"), {});
                    myModal.show();
                    setTimeout(function() {myModal.hide();}, 3500);
                }
            }).catch(e => {
                console.log(e)
            });
        },
        resolveChancellor({idCard}){
            this.chooseCardToKeep = false;

            const arrayHand = Object.values(this.hand);
            const cardKeyToKeep = arrayHand.indexOf(idCard);

            arrayHand.splice(cardKeyToKeep, 1);

            this.game.players[this.idUser].hand = [
                this.hand[cardKeyToKeep]
            ];

            this.$axios.post('/api/resolvechancellor', {
                game: this.game,
                idPlayer: this.idUser,
                idCards: arrayHand,
            }).then(response => {
                console.log(response)
            });
        },
        selectCardToGuess(levelCardToGuess, ev){
            this.levelCardToGuess = levelCardToGuess;
            let el_cards_guess_selected = document.getElementsByClassName("card-guess");
            for (let el of el_cards_guess_selected) {
                el.classList.remove("card-guess-selected");
            }
            ev.target.classList.add("card-guess-selected");
        },
        guessCard(){
            if (this.levelCardToGuess){
                let myModalEl = document.getElementById('showCardsToGuess');
                let modal = bootstrap.Modal.getInstance(myModalEl)
                modal.hide();
                let el_cards_guess_selected = document.getElementsByClassName("card-guess");
                for (let el of el_cards_guess_selected) {
                    el.classList.remove("card-guess-selected");
                }
                this.resolvePlayedCard({idCard: this.playedCard.idCard, idRival: this.idRival_GuessCard, levelCardToGuess: this.levelCardToGuess, setFalseOnTypeRes: true});
            }else{
                console.log('escoger carta')
            }
        },
        // winRound(winPlayer1, winPlayer2 = null){
        //     console.log(winPlayer1.idPlayer);
        //     console.log("Actualizar");
        //     // let update_obj = Object.values(this.game.players).findIndex((obj => obj.id == winPlayer.idPlayer));
        //     // console.log(update_obj);
        //     this.game.players[winPlayer1.idPlayer].roundWins = winPlayer1.roundWins + 1;
        //
        //     if(winPlayer2 != null){
        //         this.game.players[winPlayer2.idPlayer].roundWins = winPlayer2.roundWins + 1;
        //     }
        //
        //     //Puntos por el espía
        //     let totalSpyPlayer = Object.values(this.game.players).filter(({spy}) => spy === true).length;
        //     if(totalSpyPlayer == 1){
        //         let spyPlayer = Object.values(this.game.players).find(({spy}) => spy === true);
        //         this.game.players[spyPlayer.idPlayer].roundWins = spyPlayer.roundWins + 1;
        //     }
        //
        //
        //     console.log(this.game);
        //
        //     if(this.game.players[winPlayer1.idPlayer].roundWins >= this.game.numMaxWins){
        //         //Finalizar la partida
        //     }else if(winPlayer2 != null){
        //         if(this.game.players[winPlayer1.idPlayer].roundWins >= this.game.numMaxWins){
        //
        //         }
        //     }else{
        //         //Siguiente ronda
        //         this.$axios.post('/api/updateround', {
        //             game: this.game,
        //         }).then(response => {
        //             console.log(response);
        //         });
        //     }
        //
        // },
        newRound(spy){
            this.$axios.post('/api/updateround', {
                game: this.game,
                idPlayer: this.idUser,
                idSpy: spy
            }).then(response => {
                console.log(response);
            });
        },
        endGame(playerWinner){
            this.$axios.post('/api/endgame', {
                idGame: this.game.idGame,
                idPlayer: playerWinner
            }).then(response => {
                if(response.data.status == "success"){
                    let name = this.game.players[response.data.winner].alias;
                    let message = "Victoria del jugador " + name;
                    this.showTitleMessage(message);
                    setTimeout(function() {
                        window.location.href = "/home";
                    }, 5000);
                    // console.log("Entra en leave");
                    // this.echo.leave('play.game.'+this.idGame);
                    // this.echo.leave('play.game.'+this.idGame+'.player.'+this.idUser);
                    // this.$router.go('/');

                }
            });
        },
        showTitleMessage(message){
            this.titleMessage = message;
            const title = document.getElementById('title-play')
            title.classList.add('show', 'show-title')
            setTimeout(function() {
                title.classList.remove('show', 'show-title');
            }, 1850);
        }
    }
}
</script>
