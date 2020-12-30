<template>
    <b-card :ref="pageReference" class="profile-card">
        <b-card-header> 
            <h4>Basic</h4>
        </b-card-header>
        <b-card-body> 
                <b-form>
                <b-form-group label="Your Name" description="This will appear on your transactions">
                    <b-form-input
                        v-model="basic.name"
                        type="text"
                        required
                    ></b-form-input>
                </b-form-group>
                </b-form>
        </b-card-body>
        <b-card-footer>
            <b-button @click="onBasicSubmit" variant="primary">Update</b-button>
        </b-card-footer>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';

    import { errorToast, successToast } from 'utils/components';

    export default {
        mixins:[
            RequestMixin,
        ],
        mounted() {
            if(this.$auth.user()) {
                const { id, name } = this.$auth.user();
                this.basic = {
                    id,
                    name
                };
            }
        },
        data() {
            return {
                pageReference: 'profileBasic',
                basic: {
                    id: null,
                    name: null
                },
            }
        },
        methods: {
            async onBasicSubmit() {
                const {role_id, email, active } = this.$auth.user();
                const { id, name } = this.basic;
                if(id) {
                    const [res, err] = await this.api.patch(`users/${id}`, {
                        name,
                        role_id,
                        email,
                        active
                    })

                    if(res) {
                        successToast(this, 'Basic profile updated');
                    }

                    if(err) {
                        errorToast(this, err);
                    }
                }
                
            },
        }
    }
</script>