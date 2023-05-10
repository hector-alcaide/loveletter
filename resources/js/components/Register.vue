<template>
    <div class="auth-div">
        <div class="auth-background" id="register-div">
            <div class="register-formdiv">
                <form>
                    <div class="row ps-5 pe-4 pt-2">
                        <div class="col-md-5 ps-2 pe-4 ms-4">
                            <input id="email" type="email" class="form-control" v-model="email" required autofocus autocomplete="off" placeholder="Email">
                        </div>
                        <div class="col-md-5 register-form-col2">
                            <input id="password" type="password" class="form-control" v-model="password" required autocomplete="off" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="row ps-5 pe-4 register-form-row2">
                        <div class="col-md-5 ps-2 pe-4 ms-4">
                            <input id="alias" type="text" class="form-control" v-model="alias" required autofocus autocomplete="off" placeholder="Alias">
                        </div>
                        <div class="col-md-5 register-form-col2">
                            <input id="name" type="text" class="form-control" v-model="name" required autofocus autocomplete="off" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="row ps-5 pe-4 ">
                        <div class="col-md-5 ps-2 pe-4 ms-4 register-form-row2">
                            <input id="surnames" type="text" class="form-control" v-model="surnames" required autofocus autocomplete="off" placeholder="Apellidos">
                        </div>
                        <div class="col-md-5 mt-3 pt-1">
                            <div class="row ps-2">
                                <div class="col-md-5 ms-1 me-1">
                                    <button type="submit" class="button_form mx-auto" @click="register">
                                        Registrarse
                                    </button>
                                </div>
                                <div class="col-md-5 ps-5">
                                    <button type="button" class="button_form button_secondary" @click="$router.push('/login')">
                                        Iniciar sesión
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
            name:"",
            surnames:"",
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
                        password: this.password,
                        name: this.name,
                        surnames: this.surnames
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
