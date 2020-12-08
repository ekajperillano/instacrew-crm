<template>
    <div class="login-container container">
        <div class="card card-default">
            <div class="card-body">
                <div>
                    <img class="center-img" src="images/instacrew-logo.png">
                </div>
                <form class="p-4" autocomplete="off" @submit.prevent="login" method="post">
                    <div class="alert alert-danger" v-if="has_error">
                        <span v-text="error_message"/>
                    </div>
                    
                    <b-input-group class="mb-3">
                        <b-input-group-prepend is-text>
                            <b-icon icon="envelope"></b-icon>
                        </b-input-group-prepend>
                        <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                    </b-input-group>
                    <div class="text-center mb-3" >
                        <button @click="reset" type="button" class="btn btn-light login-btn">REQUEST PASSWORD RESET</button>
                    </div>
                    <p class="forgot-password text-right mt-2 mb-0">
                        <router-link to="/login">Back to login page</router-link>
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
                has_error: null ,
                error_message: 'Something went wrong'
            }
        },

        mounted() {

        },

        methods: {
            reset() {
                let app = this
                app.has_error = false;

                app.$http({
                    url: `password-reset/request`,
                    method: 'POST',
                    data: {
                        'email': app.email
                    }
                }).then((res) => {
                    app.$bvModal.msgBoxOk('Reset Password Request Sent', {
                        centered: true,
                        okVariant: 'success',
                    }).then((val) => {
                        app.$router.push('/login');
                    });
                }, (err) => {
                    app.loader.hide();
                    app.has_error = true;
                })
            }
        }
    }
</script>