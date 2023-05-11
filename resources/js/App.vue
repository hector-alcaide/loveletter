<template>
    <div class="bg-color1" v-if="isLoggedin">
        <router-view></router-view>
    </div>
    <div class="bg-color2" v-if="isLoggedin == false">
        <router-view></router-view>
    </div>
</template>
<script>
import Echo from 'laravel-echo';
import Pusher from "pusher-js";
export default {
    name: "App",
    data() {
        return {
            isLoggedin: false,
        }
    },
    created() {
        if(window.Laravel.isLoggedin){
            this.isLoggedin =true;
        }
    },
    mounted(){
    },
    methods: {
        mouseOver: function() {
        this.active = !this.active;
        if(this.active == true){
            document.getElementById('markerContent').style.display = 'block';
        }else{
            document.getElementById('markerContent').style.display = 'none';
        }
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
        marker(){
            if (this.cont == 0){
                document.getElementById('markerContent').style.display = 'block';
                this.cont = 1
            }else{
                document.getElementById('markerContent').style.display = 'none';
                this.cont = 0
            }
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
    },
}
</script>
