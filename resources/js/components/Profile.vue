<template>
    <div class="container">
        <div class="marker" >
            <div id="markerContent" class="markerContent" >
                <div class="text-center">
                    <a class="text-1 fs-3 mt-lg-2 mb-lg-4 logout" @click="logout">Logout</a>
                </div>
                <div class="requestFriend" v-if="requestAlias !== ''">
                    <div class="text-center my-lg-3" v-for="item in arrayRequests">
                        <label class="text-1 fs-5">Solicitud Amistad de {{item.alias}}</label>
                        <form class="d-inline mx-1" @submit.prevent="acceptInvitation(item.id)">
                            <button class="shield d-inline mx-4" type="submit"><div class="shield_yes mt-lg-1"></div></button>
                        </form>
                        <form class="d-inline mx-1" @submit.prevent="rejectInvitation(item.id)">
                            <button class="shield d-inline mx-4" type="submit"><div class="shield_no mt-lg-1"></div></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="markerLabel">
                <img @click="marker" src="../../images/marker.png">
                <label v-if="contador > 0" @click="marker" class="text-2 fs-2">{{contador}}</label>
            </div>
        </div>
        <div class="bg-image1">
            <div class="text-center">
                <a href="Home.vue"><img class="logo" src="../../images/logo.png"></a>
            </div>
            <div class="divReturn">
                <button class="d-inline return button_secondary" @click="$router.push('/home')">Volver</button>
                <h1 class="text-1 title-profile">Perfil</h1>
            </div>
            <div class="mx-auto mt-lg-5 text-center" style="width: 75%; padding-bottom: 6rem;">
                <div class="text-center d-inline-block">
                    <form @submit.prevent="findAlias()">
                        <div class="d-inline-block">
                            <label class="d-block text-1 fs-4 my-lg-3 me-lg-4">Alias: </label>
                            <label class="d-block text-1 fs-4 my-lg-3 me-lg-4">Email: </label>
                            <label class="d-block text-1 fs-4 my-lg-3 me-lg-4">Contraseña: </label>
                            <label class="d-block text-1 fs-4 my-lg-3 me-lg-4">Nombre: </label>
                            <label class="d-block text-1 fs-4 my-lg-3 me-lg-4">Apellidos: </label>
                        </div>
                        <div class="d-inline-block">
                            <input class="d-block styleInput mx-lg-3" type="text" id="alias" v-model="alias" required>
                            <input class="d-block styleInput mx-lg-3" type="text" id="email" v-model="email" required>
                            <input class="d-block styleInput mx-lg-3" type="password" id="password" v-model="password">
                            <input class="d-block styleInput mx-lg-3" type="text" id="name" v-model="name">
                            <input class="d-block styleInput mx-lg-3" type="text" id="surnames" v-model="surnames">
                        </div>
                        <div v-if="aliasEmailBusy == 1">
                            <label class="text-danger text-1 fs-3">Este {{aliasEmail}} ya está en uso, introduce otro.</label>
                        </div>
                        <div class="mt-lg-4">
                            <button type="submit" class="return mx-lg-4">Actualizar</button>
                        </div>
                    </form>
                </div>
                <div class="d-inline-block div-king">
                    <img class="" src="../../images/king.png">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        this.contador = 0
        return {
            alias: "",
            email: "",
            password: "",
            name: "",
            surnames: "",
            aliasEmailBusy: 0,
            aliasEmail: "",
            arrayRequests: [],
            requestAlias: "",
            requestId: "",
            solicitud: ""
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
                    this.name = response.data[0].name;
                    this.surnames = response.data[0].surnames;
                })
                .catch(function (error) {
                    console.error(error);
                });
        });
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('/api/requestFriend', {
            })
                .then(response => {
                    console.log(response)
                    this.arrayRequests = response.data;
                    this.contador = response.data.length;

                    response.data.forEach(res =>{
                        this.requestAlias = res.alias;
                        this.requestId = res.id;
                    });
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
                    name: this.name,
                    surnames: this.surnames
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
                    name: this.name,
                    surnames: this.surnames
                })
                    .then(response => {
                        console.log(response);
                        location.reload();
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        acceptInvitation(requestId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/acceptRequestInvitation', {
                    solicitud: requestId
                })
                    .then(response => {
                        console.log(response)
                        location.reload();
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },
        rejectInvitation(requestId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/rejectRequestInvitation', {
                    solicitud: requestId
                })
                    .then(response => {
                        console.log(response)
                        location.reload();
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            });
        },
        logout(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },

    }
}
</script>

<style scoped>

</style>
