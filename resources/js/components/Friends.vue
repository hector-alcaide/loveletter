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
                <button class="d-block return buttonClose" @click="$router.push('/home')">Volver</button>
            </div>
            <div class="mx-auto mt-lg-5" style="width: 80%; padding-bottom: 3.3rem;">
                <div class="d-inline">
                    <img class="guardia" style="padding-top: 3rem;" src="../../images/prince1.png">
                </div>
                <div class="myFriends d-inline">
                    <div class="d-block text-center">
                        <label class="text-1 titleFriend">Mis Amigos</label>
                    </div>
                    <div class="text-1 fs-4 mx-lg-4 d-inline" v-for="item in arrayFriends">
                        <label class="text-1 mt-lg-1 mx-lg-5">{{item.friend_id}}</label>
                    </div>
                    <div v-if="!arrayFriends.length">
                        <label class="text-1 my-lg-3 mx-lg-5"></label>
                    </div>
                    <div class="text-center searchFriend">
                        <form @submit.prevent="searchFriend()">
                            <div>
                                <label class="text-1 fs-2 mt-lg-4">Buscar Amigos</label>
                            </div>
                            <div>
                                <label class="text-1 fs-4 my-lg-3 me-lg-4">Introduce Alias: </label>
                                <input class="styleInput mx-lg-3" type="text" id="alias" v-model="alias">
                                <button type="submit" class="return mx-lg-4">Buscar</button>
                            </div>
                        </form>
                    </div>
                    <div class="text-center mt-lg-4" v-if="userFriend !== '' && requestSend == 0 ">
                        <label class="d-inline text-1 fs-4 my-lg-3 mx-lg-5">{{userFriend}}</label>
                        <form class="d-inline">
                            <input type="hidden" v-model="idFriend">
                            <button type="submit" class="button_addFriend" @click="addFriend">Solicitar Amistad</button>
                        </form>
                    </div>
                    <div class="text-center mt-lg-4" v-if="requestSend == 1 ">
                        <label class="d-inline text-1 fs-4 my-lg-3 mx-lg-5">{{ message }}</label>
                    </div>
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
            userFriend: "",
            requestSend: 0,
            idFriend: "",
            error: null,
            arrayFriends: "",
            arrayRequests: [],
            requestAlias: "",
            requestId: "",
            solicitud: "",
            message: ""
        }
    },
    mounted(){
        console.log(this.arrayFriends);
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('api/yourFriends', {
            })
                .then(response => {
                    this.arrayFriends = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        });
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('/api/requestFriend', {
            })
                .then(response => {
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
        searchFriend(e){
            this.requestSend = 0;
            //e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/searchFriend', {
                    alias: this.alias
                }).then(response => {
                        
                    this.$axios.post('api/searchNewFriend', {                            
                        idUser: response.data[0].idUser                            
                    }).then(response => {
                        if(response.data == 0){
                            this.requestSend = 1;
                            this.message = `No puedes solicitar amistad contigo mismo.`;
                        }else if(response.data == 1){
                            this.requestSend = 1;
                            this.message = `El usuario ${this.alias} ya es tu amigo.`;
                        }else if(response.data == 2){
                            this.requestSend = 1;
                            this.message = `La amistad con ${this.alias} está pendiente de aprobar.`;
                        }else{
                            this.requestSend = 0;
                            this.message = "";
                            this.userFriend = response.data[0].alias;
                            this.idFriend = response.data[0].idUser;
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
                })
                .catch(function (error) {
                    console.error(error);
                });
            })
        },
        addFriend(e){
            e.preventDefault()
            console.log(this.idFriend)
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/addFriend', {
                    newFriend: this.idFriend
                })
                    .then(response => {
                        this.requestSend = 1;
                        this.message = `Amistad enviada a ${this.userFriend}.`;
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
