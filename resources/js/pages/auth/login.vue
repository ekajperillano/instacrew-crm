<template>
    <div class="login-container container">
        <div class="card card-default">
            <div class="card-body">
                <div>
                    <img class="center-img" src="images/instacrew-logo.png">
                </div>
                <form class="p-4" autocomplete="off" @submit.prevent="login" method="post">
                    <div class="alert alert-danger" v-if="has_error">
                        <span v-text="login_error"/>
                    </div>
                    
                    <b-input-group class="mb-3">
                        <b-input-group-prepend is-text>
                            <b-icon icon="envelope"></b-icon>
                        </b-input-group-prepend>
                        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                    </b-input-group>
                    <b-input-group class="mb-3">
                        <b-input-group-prepend is-text>
                            <b-icon icon="lock"></b-icon>
                        </b-input-group-prepend>
                        <input type="password" id="password" class="form-control" v-on:keyup.enter="login" v-model="password" required>
                    </b-input-group>
                    <div class="text-center mb-3"  >
                        <button :disabled="!email || !password" @click="login" type="button" class="btn btn-light login-btn">LOGIN</button>
                    </div>
                    <p class="forgot-password text-right mt-2 mb-0">
                        <router-link to="/forgot-password">Forgot password ?</router-link>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                email: null,
                password: null,
                has_error: false,
                login_error: 'Error, can not connect with these credentials.'
            }
        },
        mounted() {

        },

        methods: {
            login() {
                var app = this
                this.$auth.login({
                    params: {
                        email: app.email,
                        password: app.password
                    },
                    success: function(response) {
                        const { headers } =  response;

                        this.$router.push({name: 'dashboard'});
                        this.$store.commit('auth/setToken', headers.authorization);
                    },
                    error: function() {
                        console.log('error');
                        app.has_error = true
                    },
                    rememberMe: true,
                    fetchUser: true
                }).catch((err) => {
                    console.log('er111r', err);
                });
            }
        }
    }
</script>
