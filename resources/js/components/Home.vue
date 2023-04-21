<template>
    <div class="text-center mt-lg-5">
        <img class="logo" src="../../images/logo.png">
    </div>
    <div class="text-center">
        <div class="d-inline-block me-lg-5">
            <img class="guardia" src="../../images/guardia1.png">
        </div>
        <div class="d-inline-block align-middle text-center my-lg-5 mx-lg-5">
            <button class="button_jugar d-block mx-auto mt-lg-5" @click="$router.push('/games')">Jugar</button>
            <button class="button_menu d-block mx-auto mt-lg-5 mb -lg-4">Perfil</button>
            <button class="button_menu d-block mx-auto my-lg-4" @click="$router.push('/amigos')">Amigos</button>
            <button class="button_menu d-block mx-auto my-lg-4">Ranking</button>
        </div>
        <div class="d-inline-block ms-lg-5">
            <img class="guardia" src="../../images/guardia2.png">
        </div>
    </div>



</template>

<script>
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
export default {
    name: "Home",
    data() {
        return {

        }
    },
    created() {
    },
    methods: {
        aceptarInvitacion(solicitudId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/aceptarSolicitudAmistad', {
                    solicitud: solicitudId
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
        rechazarInvitacion(solicitudId){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/rechazarSolicitudAmistad', {
                    solicitud: solicitudId
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


    }
}
</script>


