<template>
    <div class="text-center mt-lg-5">
        <img class="logo" src="../../images/logo.png">
    </div>
    <div class="solicitudAmistad" v-if="solicitudAlias !== ''">
        <div v-for="item in arraySolicitudes">
            <label>El usuario {{item.alias}} te envía una solicitur de amistad</label>
            <form @submit.prevent="aceptarInvitacion(item.id)">
                <button class="button_aceptar d-inline" type="submit">Aceptar</button>
            </form>
            <form @submit.prevent="rechazarInvitacion(item.id)">
                <button class="button_rechazar d-inline">Rechazar</button>
            </form>
        </div>
    </div>
    <div class="text-center">
        <div class="d-inline-block me-lg-5">
            <img class="guardia" src="../../images/guardia1.png">
        </div>
        <div class="d-inline-block align-middle text-center my-lg-5 mx-lg-5">
            <button class="button_jugar d-block mx-auto mt-lg-5">Jugar</button>
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
            arraySolicitudes: [],
            solicitudAlias: "",
            solicitudId: "",
            solicitud: ""
        }
    },
    created() {
    },
    mounted(){
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.post('api/solicitudAmistad', {
            })
                .then(response => {
                    console.log(response)
                    this.arraySolicitudes = response.data;

                    response.data.forEach(res =>{
                        this.solicitudAlias = res.alias;
                        this.solicitudId = res.id;
                    });
                })
                .catch(function (error) {
                    console.error(error);
                });
        });

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


