<template>
    <form>
        <button type="submit" @click="newGame">Crear partida</button>
    </form>
<!--    <form>-->
<!--        <button type="submit" @click="newGame">Generar mazo</button>-->
<!--    </form>-->
</template>

<script>
export default {
    methods: {
        newGame(e){
            e.preventDefault()
            this.$axios.post('api/newgame', {
                alias: ';'
            }).then(response => {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
        }
    }
}
</script>
