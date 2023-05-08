<template>
    <div class="container">
        <div class="bg-image1">
            <div class="text-center">
                <img class="logo" src="../../images/logo.png">
            </div>
            <div class="text-center mt-lg-4">
                <div class="d-inline-block me-lg-5" style="padding-bottom: 8rem;">
                    <img class="guardia" src="../../images/left_guard.png">
                </div>
                <div class="d-inline-block align-middle text-center mt-lg-3 pb-lg-5 mx-lg-5">
                    <button class="button_jugar d-block mx-auto mt-lg-5" @click="$router.push('/games')">Jugar</button>
                    <button class="button_menu d-block mx-auto mt-lg-5 mb -lg-4" @click="$router.push('/profile')">Perfil</button>
                    <button class="button_menu d-block mx-auto my-lg-4" @click="$router.push('/friends')">Amigos</button>
                    <button class="button_menu d-block mx-auto my-lg-4" @click="$router.push('/board')">Ranking</button>
                </div>
                <div class="d-inline-block ms-lg-5">
                    <img class="guardia" src="../../images/right_guard.png">
                </div>
            </div>
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


    }
}
</script>


