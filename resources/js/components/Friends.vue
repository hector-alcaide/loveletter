<template>
    <div class="bg-image1">
        <div class="text-center mt-lg-2">
            <a href="Home.vue"><img class="logo" src="../../images/logo.png"></a>
        </div>
        <div class="divReturn">
            <button class="d-block return buttonClose" @click="$router.push('/home')">Volver</button>
        </div>
        <div class="mx-auto mt-lg-5" style="width: 80%; padding-bottom: 7rem;">
            <div class="d-inline">
                <img class="guardia" style="padding-top: 3rem;" src="../../images/prince.png">
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
                    <label class="d-inline text-1 fs-4 my-lg-3 mx-lg-5">Amistad enviada a {{userFriend}}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            alias: "",
            userFriend: "",
            requestSend: 0,
            idFriend: "",
            error: null,
            arrayFriends: "",
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
    },
    methods: {
        searchFriend(e){
            //e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/searchFriend', {
                    alias: this.alias
                })
                    .then(response => {
                        response.data.forEach(res =>{
                            this.requestSend = 0;
                            this.userFriend = res.alias;
                            this.idFriend = res.idUser;
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
                        console.log(response)
                        this.requestSend = 1;
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
