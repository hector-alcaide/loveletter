<template>
    <div class="text-center mt-lg-5">
        <img class="logo" src="../../images/logo.png">
    </div>
    <div class="amigosVolver">
        <button class="d-block volver" @click="$router.push('/home')">Volver</button>
    </div>
    <div class="mx-auto mt-lg-5" style="width: 80%">
        <div class="d-inline">
            <img class="guardia" src="../../images/principe.png">
        </div>
        <div class="misAmigos d-inline">
            <div class="d-block text-center">
                <label class="text-1 tituloAmigos">Mis Amigos</label>
            </div>
            <div class="text-1 fs-4 mx-lg-4 d-inline" v-for="item in arrayAmistades">
                <label class="text-1 my-lg-3 mx-lg-5">{{item.amigo_id}}</label>
            </div>
            <div class="text-center buscarAmigo">
                <form @submit.prevent="buscaAmigo()">
                    <div>
                        <label class="text-1 fs-1 mt-lg-5">Buscar Amigos</label>
                    </div>
                    <div>
                        <label class="text-1 fs-3 my-lg-3 me-lg-4">Introduce Alias: </label>
                        <input class="amigosInput mx-lg-3" type="text" id="alias" v-model="alias">
                        <button type="submit" class="volver mx-lg-4">Buscar</button>
                    </div>
                </form>
            </div>
            <div class="text-center mt-lg-5" v-if="amigoUser !== '' ">
                <label class="d-inline text-1 fs-4 my-lg-3 mx-lg-5">{{amigoUser}}</label>
                <form class="d-inline">
                    <input type="hidden" v-model="idAmigo">
                    <button type="submit" class="button_addAmigo " @click="addAmigo">Solicitar Amistad</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            alias: "",
            amigoUser: "",
            idAmigo: "",
            error: null,
            arrayAmistades: ""
        }
    },
    mounted(){
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('api/tusAmigos', {
            })
                .then(response => {
                    this.arrayAmistades = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        });
    },
    methods: {
        buscaAmigo(e){
            //e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/amigo', {
                    alias: this.alias
                })
                    .then(response => {
                        response.data.forEach(res =>{
                            this.amigoUser = res.alias;
                            this.idAmigo = res.idUsuario;
                        });
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        addAmigo(e){
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/addAmigo', {
                    idAmigo: this.idAmigo
                })
                    .then(response => {
                        console.log(response)

                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    }
}
</script>

<style scoped>

</style>
