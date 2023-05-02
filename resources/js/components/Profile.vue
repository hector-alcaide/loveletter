<template>
    <div class="text-center mt-lg-5">
        <a href="Home.vue"><img class="logo" src="../../images/logo.png"></a>        
    </div>
    <div class="amigosVolver">
        <button class="d-inline volver button_cerrar" @click="$router.push('/home')">Volver</button>
        <h1 class="text-1 titulo-perfil">Perfil</h1>
    </div>
    <div class="mx-auto mt-lg-5 text-center" style="width: 80%">
        <div class="text-center d-inline-block">
            <form @submit.prevent="findAlias()">
                <div class="d-inline-block">
                    <label class="d-block text-1 fs-3 my-lg-4 me-lg-4">Alias: </label>
                    <label class="d-block text-1 fs-3 my-lg-4 me-lg-4">Email: </label>
                    <label class="d-block text-1 fs-3 my-lg-4 me-lg-4">Contraseña: </label>
                    <label class="d-block text-1 fs-3 my-lg-4 me-lg-4">Nombre: </label>
                    <label class="d-block text-1 fs-3 my-lg-4 me-lg-4">Apellidos: </label>
                </div>
                <div class="d-inline-block">                    
                    <input class="d-block amigosInput mx-lg-3 my-lg-3" type="text" id="alias" v-model="alias" required>
                    <input class="d-block amigosInput mx-lg-3 my-lg-3" type="text" id="email" v-model="email" required>
                    <input class="d-block amigosInput mx-lg-3 my-lg-3" type="password" id="password" v-model="password">
                    <input class="d-block amigosInput mx-lg-3 my-lg-3" type="text" id="nombre" v-model="nombre">
                    <input class="d-block amigosInput mx-lg-3 my-lg-3" type="text" id="apellido" v-model="apellidos">
                </div>
                <div v-if="aliasEmailBusy == 1">
                    <label class="text-danger text-1 fs-3">Este {{aliasEmail}} ya está en uso, introduce otro.</label>
                </div>
                <div class="mt-lg-4">
                    <button type="submit" class="volver mx-lg-4">Actualizar</button>
                </div>
            </form>
        </div>
        <div class="d-inline-block div-king">
            <img class="" src="../../images/rey3.png">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            alias: "",
            email: "",
            password: "",
            nombre: "",
            apellidos: "",
            aliasEmailBusy: 0,
            aliasEmail: ""
        }
    },
    mounted(){
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('api/yourProfile', {
            })
                .then(response => {
                    this.alias = response.data[0].alias;
                    this.email = response.data[0].email;
                    this.password = response.data[0].password;
                    this.nombre = response.data[0].nombre;
                    this.apellidos = response.data[0].apellidos;
                })
                .catch(function (error) {
                    console.error(error);
                });
        });
    },
    methods: {
        findAlias(e){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/findAlias', {
                    alias: this.alias,
                    email: this.email,
                    password: this.password,
                    nombre: this.nombre,
                    apellidos: this.apellidos                    
                })
                    .then(response => {
                        if(response.data == 1){
                            this.aliasEmailBusy = 1;
                            this.aliasEmail = "Alias";
                        }else if(response.data == 2){
                            this.aliasEmailBusy = 1;
                            this.aliasEmail = "Email";
                        }else{
                            this.aliasEmailBusy = 0;
                            this.updateProfile()
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })            
            
        },        
        updateProfile(e){           
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/updateProfile', {
                    alias: this.alias,
                    email: this.email,
                    password: this.password,
                    nombre: this.nombre,
                    apellidos: this.apellidos
                })
                    .then(response => {
                        console.log(response);
                        location.reload();
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
