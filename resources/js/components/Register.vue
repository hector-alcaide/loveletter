<template>
<!--    <div class="container">-->
<!--        <div class="row jutify-content-center">-->
<!--            <div class="col-md-8">-->
<!--                {{alias}}-->
<!--                <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">-->
<!--                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
<!--                    <strong>{{error}}</strong>-->
<!--                </div>-->


<!--                <div class="card card-default">-->
<!--                    <div class="card-header"><h5>Register New User</h5></div>-->
<!--                    <div class="card-body">-->
<!--                        <form>-->


<!--                            <div class="form-group row">-->
<!--                                <label for="alias" class="col-sm-4 col-form-label text-md-right">Alias</label>-->
<!--                                <div class="col-md-8">-->
<!--                                    <input id="alias" type="text" class="form-control" v-model="alias" required-->
<!--                                           autofocus autocomplete="off"  placeholder="Enter your alias">-->
<!--                                </div>-->
<!--                            </div>-->


<!--                            <div class="form-group row mt-1">-->
<!--                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>-->
<!--                                <div class="col-md-8">-->
<!--                                    <input id="email" type="email" class="form-control" v-model="email" required-->
<!--                                           autofocus autocomplete="off" placeholder="Enter your email">-->
<!--                                </div>-->
<!--                            </div>-->




<!--                            <div class="form-group row mt-1">-->
<!--                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>-->
<!--                                <div class="col-md-8">-->
<!--                                    <input id="password" type="password" class="form-control" v-model="password"-->
<!--                                           required autocomplete="off" placeholder="Enter your password">-->
<!--                                </div>-->
<!--                            </div>-->


<!--                            <div class="form-group row mt-1 mb-0">-->
<!--                                <div class="col-md-8 offset-md-4">-->
<!--                                    <button type="submit" class="btn btn-success" @click="register">-->
<!--                                        Register-->
<!--                                    </button>-->
<!--                                </div>-->
<!--                            </div>-->


<!--                            <div class="row mt-1">-->
<!--                                <div class="col-md-8 offset-md-4">-->
<!--                                    <small class="text-muted">-->
<!--                                        Have an account? Please-->
<!--                                        <router-link to="/login" >login</router-link>-->
<!--                                    </small>-->
<!--                                </div>-->
<!--                            </div>-->




<!--                        </form>-->
<!--                    </div>-->
<!--                </div>-->


<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div>-->
<!--        <router-link to="/login">Login</router-link>-->
<!--    </div>-->

    <div class="auth-div">
        <div class="auth-background" id="register-div">
            <div class="register-formdiv">
                <form>
                    <div class="row ps-5 pe-4 pt-2 mb-2">
                        <div class="col-md-6 ps-5 pe-3">
                            <input id="email" type="email" class="form-control" v-model="email" required autofocus autocomplete="off" placeholder="Email">
                        </div>
                        <div class="col-md-6 ps-5 pe-3">
                            <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="row ps-5 pe-4 pt-2 mb-3">
                        <div class="col-md-6 ps-5 pe-3">
                            <input id="alias" type="text" class="form-control" v-model="alias" required autofocus autocomplete="off" placeholder="Alias">
                        </div>
                        <div class="col-md-6 ps-5 pe-3">
                            <input id="name" type="text" class="form-control" v-model="name" required autofocus autocomplete="off" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="row ps-5 pe-4 pt-2 mb-3">
                        <div class="col-md-6 ps-5 pe-3">
                            <input id="surnames" type="text" class="form-control" v-model="surnames" required autofocus autocomplete="off" placeholder="Apellidos">
                        </div>
                    </div>
<!--                    <div class="row mt-3 pt-1 ps-1 pe-4">-->
<!--                        <div class="col-md-5">-->
<!--                            <button type="submit" class="button_form mx-auto" @click="register">-->
<!--                                Registrarse-->
<!--                            </button>-->
<!--                        </div>-->
<!--                        <div class="col-md-5">-->
<!--                            <button type="button" class="button_form button_secondary" @click="$router.push('/login')">-->
<!--                                Iniciar sesión-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </div>-->
                </form>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: "Register"
    ,data() {
        return {
            alias:"",
            email:"",
            password:"",
            error: null
        }
    },
    methods: {
        register(e){
            e.preventDefault()
            if(this.password.length > 0) {
                this.$axios.get('/sanctum/csrf-cookie').then(response => {
                    this.$axios.post('api/register', {
                        alias: this.alias,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            if (response.data.success) {
                                window.location.href = "/login"
                            } else {
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


<style scoped>


</style>
