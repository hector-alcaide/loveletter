<template>
    <h2>Jugar partida</h2>

    <p>Partida numero {{ $route.params.idPartida }}</p>




</template>

<script>
import Echo from "laravel-echo";

export default {
    data() {
        return {
        }
    },
    mounted() {
        let echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true
        })
    },
    methods: {

    }
}
</script>
