<template>
    <div class="modal">
        <div class="modal-header">
            <span>Invite User</span>
            <button  @click.prevent="close" class="float-right close-modal btn btn-outline-info" >
                <em class="fa fa-times" aria-hidden="true"/>
            </button>
        </div>
        <div class="modal-body">
            <div class="mb-2 d-flex align-items-center">
                <span class="w-50"> Email </span>
                <b-input class="w-100" v-model="user.email"/>
            </div>
            <div class="mb-2 d-flex align-items-center">
                <span class="w-50"> Name </span>
                <b-input class="w-100" v-model="user.name" />
            </div>
            <div class="mb-2 d-flex align-items-center">
                <span class="w-50"> Role </span>
                <v-select
                    placeholder="Add role to user"
                    class="w-100"
                    v-model="user.role"
                    label="name"
                    @search="fetchRoles"
                    :options="options.role"
                >
                    <template slot="no-options">Add role to user</template>
                </v-select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" @click="invite" class="btn btn-info">Send Email Invite</button>
        </div>
    </div>
</template>
<script>

    import vSelect from 'vue-select';

    import RequestMixin from 'mixins/requestMixin';
    import ModalMixin from 'mixins/modalMixin';

    import { successToast, errorToast } from 'utils/components.js';
    import { DEBOUNCE_WAIT } from 'configs/constants.js';

    export default {
        mixins: [
            RequestMixin,
            ModalMixin
        ],
        mounted() {
            this.fetchRoles()
        },
        components : {
            vSelect
        },
        props : {
            inviteEvent : {
                type: String,
                default: 'confirm-invite'
            },
        },
        data () {
            return {
                pageReference: 'inviteUserModal',
                user : {
                    name: '',
                    email: '',
                    role: null,
                },
                options: {
                    role: [],
                },
            }
        },
        methods : {
            async invite() {
                const {name, email, role } = this.user;
                let [res, err] = await this.api.post('users/invite', {
                    name,
                    email,
                    role_id: (role) ? role.id : null
                });
                
                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'User Invited');
                    this.$events.fire(this.inviteEvent, true)
                    this.$emit('close');
                }
            },
            fetchRoles(search = null, loading = null) {
                if(loading) loading(true);
      			this.searchRole(loading, search, this);
    		},
			searchRole: _.debounce((loading, search, app) => {
				app.$http.get('role/options', {
					params: {
						search
					}
				}).then(res => {
					app.options.role = res.data
                    if(loading) loading(false);
				});
            }, DEBOUNCE_WAIT),
        },
    }
</script>
