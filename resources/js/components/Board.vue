<template>
    <div class="board">
        <div class="container-cards">
            <div class="container-cards-row-1">
                <div id="div-player-3" class="div-player-3 text-center">
                    <div>
                        <label class="text-2">{{alias_3}}</label>
                    </div>
                    <img id="card3-down" src="../../images/back-card.jpg" style="width: 90px" @click="rotateCard">
                    <img id="card3-up" class="" src="../../images/cards/card2.jpg" style="width: 90px; display: none;" @click="rotateCard">

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
                    <div class="myCards" style="height: 250px;">
                        <img class="mx-2" src="../../images/cards/card2.jpg">
                        <img class="mx-2" src="../../images/cards/card3.jpg">
                        <!-- <img class="mx-2" src="../../images/cards/card3.jpg"> -->
                        <!-- <img class="mx-2" src="../../images/cards/card5.jpg" style="width: 150px"> -->
                    </div>                    
                    <div>
                        <label id="myName" class="text-2" style="margin-top: 30px">{{alias_1}}</label>
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
        <div class="board_buttons">
            <button class="mx-lg-3" onclick="window.open('https://www.baobablibros.es/archivos/love-letter-reglas-del-juego.pdf')">?</button>
            <button class="mx-lg-3 end_board" @click="$router.push('/home')">X</button>
        </div>
        <div class="container-frame">
            <div>
                <label class="text-2 fs-4 mt-lg-3">Turno de Jake</label>
            </div>
            <div>
                <label class="text-2 fs-4"><u>Última tirada:</u></label>
                <label class="text-2 fs-5">Míriam ha usado barón. Ha comparado carta con Hector y han empatado. Ninguno pierde</label>
            </div>
            <div >
                <label class="text-2 d-block fs-4">Puntos:</label>
                <label class="text-2 d-inline-block fs-5 ms-lg-3 me-lg-2">Jake 2</label>
                <label class="text-2 d-inline-block fs-5 ms-lg-3 me-lg-2">Hector 2</label>
                <label class="text-2 d-inline-block fs-5 ms-lg-3 me-lg-2">David 1</label>
            </div>
            <div>
                <label class="text-2 fs-4">Puntos para victoria: 5</label>
            </div>
        </div>
        <button @click="poner">Poner</button>
    </div>
</template>

<script>
export default {
    name: "Board",
    cartas: [],
    data(){
        return{
            alias_1: "",
            alias_2: "",
            alias_3: "",
            alias_4: "",
            alias_5: "",
            alias_6: ""
        }
    },
    mounted(){
        this.$emit('inMarker',false);
        
        this.turnCard = 0,
        this.positionPlayers();

        //Depende de los jugadores que haya ocultar al resto
        this.playersNumber = "";
        if(this.playersNumber == 2){
            document.getElementById('div-player-3').style.display = 'none';
            document.getElementById('div-player-4').style.display = 'none';
            document.getElementById('div-player-5').style.display = 'none';
            document.getElementById('div-player-6').style.display = 'none';
        }else if(this.playersNumber == 3){
            document.getElementById('div-player-4').style.display = 'none';
            document.getElementById('div-player-5').style.display = 'none';
            document.getElementById('div-player-6').style.display = 'none';
        }else if(this.playersNumber == 4){
            document.getElementById('div-player-5').style.display = 'none';
            document.getElementById('div-player-6').style.display = 'none';
        }else if(this.playersNumber == 5){
            document.getElementById('div-player-6').style.display = 'none';
        }
    },
    methods:{
        poner(e){
            //e.preventDefault()
            this.cartas = Array.from({length: 5}, () => Math.floor(Math.random() * 5));

            let div = document.getElementById('tiradas');
            this.cartas.forEach(res =>{
                div.innerHTML +=
                    `<img class="carta${res}" src="http://[::1]:5173/resources/images/cards/card${res}.jpg" style="width: 100px">`;
            });


            console.log(this.cartas);
        },
        rotateCard(){
            console.log(this.turnCard)
            if(this.turnCard == 0){
                document.getElementById('card3-down').style.display = 'none';
                document.getElementById('card3-up').style.display = 'block';
                this.turnCard = 1;
            }else{
                document.getElementById('card3-down').style.display = 'block';
                document.getElementById('card3-up').style.display = 'none';
                this.turnCard = 0;
            }
        },
        positionPlayers(){
            //Coger posiciones de los jugadores. Coger como primera posición para mostrar en el tablero a tu usuario.
            this.alias_1 = "Jake";
            //Después poner los siguientes jugadores en este orden.
            this.alias_5 = "David";
            this.alias_3 = "Ferran";
            this.alias_2 = "Hector";
            this.alias_4 = "Luis";
            this.alias_6 = "Míriam";
            //Las tiradas deberán seguir este orden
        }
    }
}
</script>

<style scoped>

</style>
