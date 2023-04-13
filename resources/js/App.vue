<template>
<!--    <div class="container">-->
<!--        <nav class="navbar navbar-expand-sm navbar-light bg-light mb-4">-->
<!--            <a class="navbar-brand" href="#">Laravel Vue 3</a>-->
<!--            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"-->
<!--                    aria-expanded="false" aria-label="Toggle navigation"></button>-->
<!--            <div class="navbar-nav" v-if="isLoggedin">-->
<!--                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>-->
<!--                <router-link to="/posts" class="nav-item nav-link">xxxx</router-link>-->
<!--                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>-->
<!--            </div>-->
<!--            <div class="navbar-nav" v-else>-->
<!--                <router-link to="/" class="nav-item nav-link">Home</router-link>-->
<!--                <router-link to="/login" class="nav-item nav-link">Login</router-link>-->
<!--                <router-link to="/register" class="nav-item nav-link">Register</router-link>-->
<!--            </div>-->
<!--        </nav>-->
<!--    </div>-->

    <div class="container background bg-image1">
        <div class="navbar-nav" v-if="isLoggedin">
            <a class="nav-item nav-link" style="cursor: pointer; background: aqua; width: 150px" @click="logout">Logout</a>
        </div>

        <router-view></router-view>
    </div>

</template>
<script>
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
    methods: {
        logout(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('api/logout')
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


        }
    },
}
</script>
