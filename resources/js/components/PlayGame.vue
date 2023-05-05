<template>
    <div class="container-cards">
        <div class="container-cards-row-1">
            <div id="div-player-3" class="div-player-3 text-center">
                <div>
                    <label class="text-2">{{alias_3}}</label>
                </div>
                <img id="card3-down" class="" src="../../images/back-card.jpg" style="width: 90px" @click="rotateCard">
                <img id="card3-up" class="" src="../../images/card2.jpg" style="width: 90px; display: none;" @click="rotateCard">
                <div class="div-extras-down">
                    <div id="protection-3" style="display: none">
                        <img class="" src="../../images/protection.png">
                    </div>
                    <div id="spy-3" style="display: none">
                        <img class="" src="../../images/spy.png">
                    </div>
                </div>
            </div>
            <div id="div-player-2" class="div-player-2 text-center">
                <div>
                    <label class="text-2">{{alias_2}}</label>
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
            </div>
            <div id="div-player-4" class="div-player-4 text-center">
                <div>
                    <label class="text-2">{{alias_4}}</label>
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
            </div>
        </div>
        <div class="container-cards-row-2">
            <div id="tiradas" class="thrown-cards">
                <!-- <img src="../../images/card1.jpg" style="width: 90px"> -->
            </div>
            <div class="mallet-cards">
                <img src="../../images/mallet_5.png" style="width: 90px">
                <label class="text-1 fs-4">15</label>
            </div>
        </div>
        <div class="container-cards-row-3">
            <div id="div-player-5" class="div-player-5 text-center">
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
                    <label class="text-2">{{alias_5}}</label>
                </div>
            </div>
            <div id="div-player-1" class="div-player-1 text-center">
                <div>
                    <div id="protection-1" style="display: none">
                        <img class="" src="../../images/protection.png">
                    </div>
                    <div id="spy-1" style="display: none">
                        <img class="" src="../../images/spy.png">
                    </div>
                </div>
                <img class="mx-2" src="../../images/card2.jpg" style="width: 90px">
                <img class="mx-2" src="../../images/card3.jpg" style="width: 90px">
                <div>
                    <label class="text-2">{{alias_1}}</label>
                </div>
            </div>
            <div id="div-player-6" class="div-player-6 text-center">
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
                    <label class="text-2">{{alias_6}}</label>
                </div>
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
        <div >
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
            users: [],
            allowSteal: false,
            allowPlayCard: false,
            // cardOnPlayer: false,
            playedCard: null,
            typesCardResolution : {
                'direct': 'default',
                'onPlayer': false,
                'onPlayerOnCard': false,
            },
            cardsResolution: {
                '0': 'direct',
                '1': 'onPlayerOnCard',
                '2': 'onPlayer',
                '3': 'onPlayer',
                '4': 'direct',
                '5': 'onPlayer',
                '6': 'direct',
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

                    let arrayPosicones = [5,3,2,4,6]

                    this.players[0] = this.game.players[this.idUser];
                    let player = this.game.players[this.idUser].playerNum;
                    let i;
                    let a = 1;
                    let b = 0;
                    for (let i = 0; i < (array.length -1); i++) {
                        b = player + a
                        if(b > 6){
                            b = 1;
                        }
                        for(let j = 0; j < arrayPosicones.length; j++){
                            if(totalJugadores >= arrayPosicones[j]){
                                this.players[arrayPosicones[i]] = this.game.playerNum[b];
                            }
                        }
                        
                        
                        /*if(i == 0){
                            this.players[5] = this.game.playerNum[b]
                        }else if(i == 1){
                            this.players[3] = this.game.playerNum[b]
                        }else if(i == 2){
                            this.players[2] = this.game.playerNum[b]
                        }else if(i == 3){
                            this.players[4] = this.game.playerNum[b]
                        }else if(i == 4){
                            this.players[6] = this.game.playerNum[b]
                        }*/
                        
                        a++;
                    }
                    /*
                    
let playerUser = 3;
let arrayPosicones = [4,2,1,3,5];
let jugadores = [1,2,3,4];
let players = [];

players[0] = playerUser;
let a = 1;
let b = 0;
let d = 0;
let z = 0;

for (let i = 0; i < (jugadores.length -1); i++) {
  b = playerUser + a
  if(b > jugadores.length){
    d++;
    b = d;    
  }  
  for(let j = z; j < arrayPosicones.length; j++){  
    if((jugadores.length-1) >= arrayPosicones[j]){
      players[arrayPosicones[j]] = b;
      z = j+1;
      break;
    }    
  }                     
  a++;
}

console.log(players[0])
console.log(players[1])
console.log(players[2])
console.log(players[3])
console.log(players[4])
console.log(players[5])   

                    */

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
        resolveChancellor({idCard}){
            const arrayHand = Object.values(this.game.players[this.idUser].hand);
            const cardKeyDelete = arrayHand.findIndex(card => card.idCard === idCard);

            delete this.game.players[this.idUser].hand[cardKeyDelete];

            this.$axios.post('/api/resolvechancellor', {
                game: this.game,
                idPlayer: this.idUser,
                idCard: idCard,
            }).then(response => {
                console.log(response)
            });
        },
    }
}
</script>
