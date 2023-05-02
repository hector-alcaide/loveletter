<template>
    <div class="auth-div">
        <div class="auth-background" id="login-div">
            <div class="auth-formdiv">
                <form class="auth-form">
                    <div class="row ps-5 pe-4 pt-2 mb-3">
                        <div class="col-md-12 ps-5 pe-3">
                            <input id="alias" type="text" class="form-control" v-model="alias" required autofocus autocomplete="off" placeholder="Alias">
                        </div>
                    </div>
                    <div class="row ps-5 pe-4 pt-2 mb-3">
                        <div class="col-md-12 pt-1 ps-5 pe-3">
                            <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="row mt-3 pe-4">
                        <div class="col-md-8">
                            <button type="submit" class="button_form mx-auto" @click="doLogin">
                                Iniciar sesión
                            </button>
                        </div>
                        <div class="col-md-4 ps-1">
                            <button type="button" class="button_form button_secondary" @click="$router.push('/register')">
                                Registrarse
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            alias: "",
            password: "",
            error: null
        }
    },
    methods: {
        doLogin(e) {
            e.preventDefault()
            if(this.password.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/login', {
                        alias: this.alias,
                        password: this.password
                    })
                        .then(response => {
                            if (response.data.success) {
                                console.log('Login OK');
                                this.$router.go('/')
                            } else {
                                console.error('No loggin');
                                this.error = response.data.message
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                })
            }
        }
    },
    beforeRouteEnter(to, from, next){
        if(window.Laravel.isLoggedin){
            return next('/');
        }
        next();
    }
}
</script>
