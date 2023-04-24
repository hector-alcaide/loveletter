<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col1 mx-3 mb-5 mt-2"></div>
            <div class="col-md-3 col1 mx-3 mb-5 mt-2">
                <img src="../../images/back-card.jpg" style="width: 150px">
            </div>
            <div class="col-md-3 col1 mx-3 mb-5 mt-2"></div>
            <div class="col-md-3 col1 mx-3 mb-5 mt-2">
                <img src="../../images/back-card.jpg" style="width: 150px">
            </div>
            <div class="col-md-3 col1 mx-3 mb-5 mt-2"></div>
        </div>
        <div class="row">
            <div class="col-md-3 col2 mx-3 mb-5">
                <img src="../../images/back-card.jpg" style="width: 150px">
            </div>
            <div class="col-md-3 col2 mx-3 mb-5"></div>
            <div id="cartas-div" class="col-md-3 col2 mx-3 mb-5 carta-tirada">

<!--                <img class="carta1" src="../../images/card1.jpg" style="width: 150px">-->
<!--                <img class="carta3" src="../../images/card3.jpg" style="width: 150px">-->
<!--                <img class="carta5" src="../../images/card5.jpg" style="width: 150px">-->
<!--                <img class="carta0" src="../../images/card0.jpg" style="width: 150px">-->
<!--                <img class="carta4" src="../../images/card4.jpg" style="width: 150px">-->
            </div>
            <div class="col-md-3 col2 mx-3 mb-5">
                <img src="../../images/mazo-6.png" style="width: 165px">
            </div>
            <div class="col-md-3 col2 mx-3 mb-5">
                <img src="../../images/back-card.jpg" style="width: 150px">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col3 mx-3 mt-2"></div>
            <div class="col-md-3 col3 mx-3 mt-2">
                <img src="../../images/card3.jpg" style="width: 150px">
            </div>
            <div class="col-md-3 col3 mx-3 mt-2"></div>
            <div class="col-md-3 col3 mx-3 mt-2">
                <img src="../../images/back-card.jpg" style="width: 150px">
            </div>
            <div class="col-md-3 col3 mx-3 mt-2">
                <button type="submit" class="button_addAmigo " @click="poner">Probar</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Board",
    cartas: [],
    data(){

    },
    methods:{
        poner(e){
            //e.preventDefault()
            this.cartas = Array.from({length: 5}, () => Math.floor(Math.random() * 5));

            let div = document.getElementById('cartas-div');
            this.cartas.forEach(res =>{

                div.innerHTML +=
                    `<img class="carta${res}" src="http://[::1]:5173/resources/images/card${res}.jpg" style="width: 150px">`;
            });


            console.log(this.cartas);
        }
    }
}
</script>

<style scoped>

</style>
