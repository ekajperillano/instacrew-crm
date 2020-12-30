<template>
    <b-card :ref="pageReference" class="profile-card">
        <b-card-header> 
            <h4>Update Password</h4>
        </b-card-header>
        <b-card-body>
            <b-form>
                <b-form-group label="New Password" description="set your new password">
                    <b-form-input type="password" v-model="password.new" required ></b-form-input>
                </b-form-group>
                <b-form-group label="Confirm Password" description="confirm your new password">
                        <b-form-input type="password" v-model="password.confirm" required ></b-form-input>
                </b-form-group>
            </b-form>
        </b-card-body>
        <b-card-footer>
            <b-button @click="onPasswordSubmit" variant="primary">Update</b-button>
        </b-card-footer>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';
    import { errorToast, successToast } from 'utils/components';

    export default {
        mixins: [
            RequestMixin
        ],
        data() {
            return {
                pageReference: 'profilePassword',
                password: {
                    new: null,
                    confirm: null
                }
            }
        },

        methods: {
            async onPasswordSubmit() {
                const { id } = this.$auth.user();
                const {new: new_password, confirm: password_confirmation} = this.password;
                let [res, err] = await this.api.patch(`users/${id}/password`, {
                    password: new_password,
                    password_confirmation
                });

                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Password Updated')
                    this.password = {
                        new: null,
                        confirm: null
                    }
                }
            },
        }
    }
</script>