<template>
    <b-card :ref="pageReference" class="vld-parent role-list">
        <b-card-header class="d-flex align-items-center">
            <v-select
                placeholder="Search or Create Role"
                class="w-50"
                v-model="selected.role"
                label="name"
                taggable
                @search="fetchRoles"
                :options="options.role"
            >
                <template slot="no-options">Search or Create Role</template>
            </v-select>
            <div v-if="selected.role" class="w-50 float-right">
                <button v-if="selected.role.id" type="button" @click.prevent="update" class="float-right ml-1 btn btn-success">Update</button>
                <button v-else type="button" @click.prevent="create" class="float-right ml-1 btn btn-success">Create</button>
            </div>
        </b-card-header>
        <b-card-body v-if="selected.role" role="tablist">
            <b-card no-body v-for="(permission, index) in permissions" :key="index" >
                <div v-if="permission && selected.role"> 
                    <b-card-header header-tag="header" class="permission-header" role="tab">
                        <b-button block v-b-toggle="'accordion-' + index" size="lg" variant="light" class="text-left">{{ permission.label.toUpperCase() }}</b-button>
                    </b-card-header>
                    <b-collapse :id="'accordion-' + index" accordion="my-accordion" role="tabpanel">
                        <b-card-body class="permission-table">
                            <b-table v-if="selected.role" 
                                hover
                                sticky-header
                                :items="permission.items"
                                :fields="permissions_table.fields"
                                responsive="sm"
                            >
                                <template v-slot:cell(allowed)="row">
                                <toggle-checkbox :value="isAllowed(row.item)" @input="(toggled) => allow(toggled, row.item)" />
                                </template>
                            </b-table>
                        </b-card-body>
                    </b-collapse>
                </div>
            </b-card>
        </b-card-body>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';

    import vSelect from 'vue-select';
    import ToggleCheckbox from 'components/common/toggle-checkbox';
    import _ from 'lodash';
    import { inArray } from 'utils/helpers.js';
    import { successToast, errorToast } from 'utils/components.js';
    import { DEBOUNCE_WAIT } from 'configs/constants.js';

    export default {
        mixins: [
            RequestMixin
        ],
        components: {
            vSelect,
            ToggleCheckbox
        },
        mounted() {
            this.fetchRoles();
            this.parsePermission();
        },
        data() {
            return {
                options : {
                    role: [],
                },
                selected : {
                    role: null
                },
                rolePermissions: [],
                permissions: [],
                permissions2: [],
                permissions_table : {
                    fields: [{
                        label: 'Permissions',
                        thClass: 'text-center',
                        key: 'description'
                    },{
                        label: 'Allowed',
                        thClass: 'text-center',
                        key: 'allowed'
                    }]
                },
                pageReference: 'roleList'
            }
        },
        methods: {
            isAllowed(data) {
                const selectedRole = this.selected.role;
                if(selectedRole && selectedRole.permissions) {
                    let selectedRolePermissionIds = [];
                    selectedRole.permissions.map(permission => {
                        selectedRolePermissionIds.push(permission.id);
                    });

                     if(inArray(selectedRolePermissionIds, data.id)) {
                        return true;
                     }
                }

                return false
            },
            async parsePermission(){
                const [res, ] = await this.api.get('permission/list');
                
                if(res) {
                    res.map((record) => {
                        let moduleIndex = record.order_code.substring(0,2) * 1;
                            
                        if(this.permissions[moduleIndex]) {
                            this.permissions[moduleIndex].items.push(record)
                        } else {
                            let label = '';
                            let key = '';
                            if(moduleIndex === 0) {
                                label = 'User Permissons';
                                key = 'user';
                            } else if(moduleIndex === 1) {
                                label = 'Client Permissons';
                                key = 'client';
                            } else if(moduleIndex === 2) {
                                label = 'Social Permissons';
                                key = 'social';
                            } else if(moduleIndex === 3) {
                                label = 'Contact Permissons';
                                key = 'contact';
                            }

                            this.permissions[moduleIndex] = {
                                label,
                                items: [record]
                            }
                        }
                    });
                }
            },
            async create() {
                const [res, err] = await this.api.post('role', {
                    name: this.selected.role,
                    permissions: this.rolePermissions
                });

                if(err) {
                    errorToast(this, err, 'Problem creating new role');
                }

                if(res) {
                    this.selected.role = res;
                    successToast(this, 'Role created');
                }
            },
            async update() {
                const [res, err] = await this.api.patch('role/' + this.selected.role.id, {
                    name: this.selected.role.name,
                    permissions: this.rolePermissions
                });

                if(err) {
                    errorToast(this, err, 'Problem updating role');
                }

                if(res) {
                    successToast(this, 'Role updated');
                    this.$auth.fetch();
                }

            },
            allow(toggled, item) {
                if(toggled == true) {
                    if(!inArray(this.rolePermissions, item.id)) {
                        this.rolePermissions.push(item.id);
                    }
                } else {
                    if(inArray(this.rolePermissions, item.id)) {
                        const index = this.rolePermissions.findIndex(permission => permission == item.id);
                        this.rolePermissions.splice(index, 1);
                    }
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
        watch: {
            'selected.role' : {
                handler(value) {
                    this.rolePermissions = [];
                    if(value && value.permissions) {
                        value.permissions.map(permission => {
                             this.rolePermissions.push(permission.id);
                        });
                    }
                },
                deep: true
            }
        }
    }
</script>