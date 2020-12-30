<template>
    <div class="modal" :ref="pageReference">
        <div class="modal-header">
            <span>Edit Customer</span>
            <button  @click.prevent="close" class="float-right close-modal btn btn-outline-info" >
                <em class="fa fa-times" aria-hidden="true"/>
            </button>
        </div>
        <div v-if="record" class="modal-body">
            <b-row class="mb-2">
                <b-col cols="4">Email</b-col>
                <b-col>
                   <b-input class="w-100" v-model="record.email"/>
                </b-col>
            </b-row>
            <b-row class="mb-2">
                <b-col cols="4">Name</b-col>
                <b-col>
                   <b-input class="w-100" v-model="record.name" />
                </b-col>
            </b-row>
            <b-row class="mb-2">
                <b-col cols="4">Role</b-col>
                <b-col>
                    <v-select
                        placeholder="Add role to user"
                        class="w-100"
                        v-model="record.role"
                        label="name"
                        @search="fetchRoles"
                        :options="options.role"
                    >
                        <template slot="no-options">Add role to user</template>
                    </v-select>
                </b-col>
            </b-row>
            <b-row v-if="!record.invite_token" class="mb-2">
                <b-col cols="4">Status</b-col>
                <b-col>
                    <checkbox-toggle v-model="record.active" show-labels  label-checked="Active" label-unchecked="Inactive"/>
                </b-col>
            </b-row>
        </div>
        <div class="modal-footer"> 
            <button type="button" @click.prevent="update" class="float-right ml-1 btn btn-info">Update</button>
            <button v-if="record && record.invite_token" type="button" @click.prevent="resend" class="float-right ml-1 btn btn-secondary">Resend Invite</button>
        </div>
    </div>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';
    import ModalMixin from 'mixins/modalMixin';

    import CheckboxToggle from 'components/common/toggle-checkbox';
	import vSelect from 'vue-select';

    import { errorToast, successToast } from 'utils/components';
    import { DEBOUNCE_WAIT } from 'configs/constants.js';

    export default {
        mixins: [
            RequestMixin,
            ModalMixin
        ],
        components: {
            CheckboxToggle,
            vSelect
        },
        props: {
            user: {
                required: true,
                type: Object
            },
            confirmEvent: {
                type: String,
                default: 'update-user'
            }
        },
        data() {
            return {
                pageReference: 'editUserModal',
                record: null,
                options: {
                    role: [],
                }
            }
        },
        mounted() {
            this.fetchRoles();
            this.record = {
                ...this.user
            }
        },
        methods: {
            async update() {
                const { name, email, active, role } = this.record;

                let [res, err] = await this.api.patch('users/' + this.record.id, {
                    name, 
                    email, 
                    active: active == true ? 1 : 0, 
                    role_id: (role) ? role.id : null});

                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'User updated.')
                    this.$emit('close');
                    this.$events.fire(this.confirmEvent);
                }
            },
            async resend() {
                let [res, err] = await this.api.patch('users/invite/' + this.record.id);
                
                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Invitation resent.')
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
        }
    }
</script>