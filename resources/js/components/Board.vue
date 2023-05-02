<template>
    <div class="container-cartas">
        <div class="container-cartas-fila1">
            <div id="div-player-3" class="div-player-3 text-center">
                <div>
                    <label class="text-2">{{alias_3}}</label>
                </div>
                <img id="card3-down" class="" src="../../images/back-card.jpg" style="width: 90px" @click="rotateCard">
                <img id="card3-up" class="" src="../../images/card2.jpg" style="width: 90px; display: none;" @click="rotateCard">
                <div class="div-extras-abajo">
                    <div id="protegido-3" style="display: none">
                        <img class="" src="../../images/protegido.png">
                    </div>
                    <div id="espia-3" style="display: none">
                        <img class="" src="../../images/espia1.png">
                    </div>
                </div>
            </div>
            <div id="div-player-2" class="div-player-2 text-center">
                <div>
                    <label class="text-2">{{alias_2}}</label>
                </div>
                <img src="../../images/back-card.jpg" style="width: 90px">
                <div class="div-extras-abajo">
                    <div id="protegido-2" style="display: none">
                        <img class="" src="../../images/protegido.png">
                    </div>
                    <div id="espia-2" style="display: none">
                        <img class="" src="../../images/espia1.png">
                    </div>
                </div>
            </div>
            <div id="div-player-4" class="div-player-4 text-center">
                <div>
                    <label class="text-2">{{alias_4}}</label>
                </div>
                <img src="../../images/back-card.jpg" style="width: 90px">
                <div class="div-extras-abajo">
                    <div id="protegido-4" style="display: none">
                        <img class="" src="../../images/protegido.png">
                    </div>
                    <div id="espia-4" style="display: none">
                        <img class="" src="../../images/espia1.png">
                    </div>
                </div>
            </div>
        </div>
        <div class="container-cartas-fila2">
            <div id="tiradas" class="cartas-tiradas">
                <!-- <img src="../../images/card1.jpg" style="width: 90px"> -->
            </div>
            <div class="mazo-cartas">
                <img src="../../images/mazo-6.png" style="width: 90px">
                <label class="text-1 fs-4">15</label>
            </div>
        </div>
        <div class="container-cartas-fila3">
            <div id="div-player-5" class="div-player-5 text-center">
                <div class="div-extras-arriba">
                    <div id="protegido-5" style="display: none">
                        <img class="" src="../../images/protegido.png">
                    </div>
                    <div id="espia-5" style="display: none">
                        <img class="" src="../../images/espia1.png">
                    </div>
                </div>
                <img src="../../images/back-card.jpg" style="width: 90px">
                <div>
                    <label class="text-2">{{alias_5}}</label>
                </div>
            </div>
            <div id="div-player-1" class="div-player-1 text-center">
                <div>
                    <div id="protegido-1" style="display: none">
                        <img class="" src="../../images/protegido.png">
                    </div>
                    <div id="espia-1" style="display: none">
                        <img class="" src="../../images/espia1.png">
                    </div>
                </div>
                <img class="mx-2" src="../../images/card2.jpg" style="width: 90px">
                <img class="mx-2" src="../../images/card3.jpg" style="width: 90px">
                <div>
                    <label class="text-2">{{alias_1}}</label>
                </div>
            </div>
            <div id="div-player-6" class="div-player-6 text-center">
                <div class="div-extras-arriba">
                    <div id="protegido-6" style="display: none">
                        <img class="" src="../../images/protegido.png">
                    </div>
                    <div id="espia-6" style="display: none">
                        <img class="" src="../../images/espia1.png">
                    </div>
                </div>
                <img src="../../images/back-card.jpg" style="width: 90px">
                <div>
                    <label class="text-2">{{alias_6}}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="container-marco">
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
    <button @click="poner">Poner</button>
    <!-- <div class="container-cartas">
        <div>
            <div class="col4 d-inline card-jugador-1">
                <img class="" src="../../images/back-card.jpg" style="width: 100px">
            </div>
            <div class="col4 d-inline">
                <img src="../../images/back-card.jpg" style="width: 100px">
            </div>
            <div class="col4 d-inline card-jugador-3">
                <img src="../../images/back-card.jpg" style="width: 100px">
            </div>
        </div>
        <div>
            <div class="col4 d-inline cartas-tiradas">
                <img src="../../images/card1.jpg" style="width: 100px">
            </div>
            <div class="col4 d-inline">
                <img src="../../images/mazo-6.png" style="width: 100px">
            </div>
        </div>

        <div class="div-tercero">
            <div class="card-jugador-4">
                <img src="../../images/back-card.jpg" style="width: 100px">
            </div>
            <div class="card-jugador-5">
                <img class="mx-2" src="../../images/card2.jpg" style="width: 100px">
                <img class="mx-2" src="../../images/card3.jpg" style="width: 100px">
                <img class="mx-2" src="../../images/card3.jpg" style="width: 100px">
            </div>
            <div class="card-jugador-6">
                <img src="../../images/back-card.jpg" style="width: 100px">
            </div>
        </div>
    </div>
    <div class="tablero-datos"></div> -->
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
            alias_6: "",
        }
    },
    mounted(){
        this.turnCard = 0, 
        this.positionPlayers()

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
                    `<img class="carta${res}" src="http://[::1]:5173/resources/images/card${res}.jpg" style="width: 100px">`;
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
