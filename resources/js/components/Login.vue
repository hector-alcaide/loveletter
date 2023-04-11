<template>
<!--    <div class="auth-div">-->
<!--        <div class="auth-background" id="login-div">-->
<!--            <div class="row justify-content-center">-->
<!--                <div class="col-md-8">-->
<!--                    <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">-->
<!--                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->


<!--                        <strong>{{error}}</strong>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="auth-form">-->
<!--                <form>-->
<!--                    <div class="form-group row">-->
<!--                        <div class="col-md-12">-->
<!--                            <input id="email" type="email" class="form-control" v-model="email" required autofocus autocomplete="off" placeholder="Email">-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="form-group row mt-3">-->
<!--                        <div class="col-md-12">-->
<!--                            <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="Contraseña">-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="form-group row mt-3">-->
<!--                        <div class="col-md-8">-->
<!--                            <button type="submit" class="button_form d-block mx-auto" @click="doLogin">-->
<!--                                Iniciar sesión-->
<!--                            </button>-->
<!--                        </div>-->
<!--                        <div class="col-md-4">-->
<!--                            <router-link to="/register" >Register</router-link>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <div class="auth-div">
        <div class="auth-background" id="login-div">
            <div class="auth-formdiv">
                <form class="auth-form">
                    <div class="row ps-5 pe-4 pt-2 mb-3">
                        <div class="col-md-12 ps-5">
                            <input id="alias" type="alias" class="form-control" v-model="alias" required autofocus autocomplete="off" placeholder="Alias">
                        </div>
                    </div>
                    <div class="row ps-5 pe-4 pt-2 mb-3">
                        <div class="col-md-12 pt-1 ps-5">
                            <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <button type="submit" class="button_form mx-auto" @click="doLogin">
                                Iniciar sesión
                            </button>
                        </div>
                        <div class="col-md-4">
                            <router-link to="/register" >Registrar se</router-link>
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
