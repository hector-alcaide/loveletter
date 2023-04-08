<template>
    <div class="auth-div">
        <div class="auth-background" id="login-div">
<!--            <div class="row justify-content-center">-->
<!--                <div class="col-md-8">-->
<!--                    <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">-->
<!--                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->


<!--                        <strong>{{error}}</strong>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="auth-form">
                <form>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control" v-model="email" required autofocus autocomplete="off" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="Contraseña">
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-success" @click="doLogin">
                                Iniciar sesión
                            </button>
                        </div>
                        <div class="col-md-4">
                            <router-link to="/register" >Register</router-link>
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
            email: "",
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
                        email: this.email,
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
