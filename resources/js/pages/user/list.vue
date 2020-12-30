<template>
    <b-card :ref="pageReference" class="vld-parent">
        <b-card-body>
            <user-datatable
            :ref="datatableRef"
            :apiUrl="datatableUrl"
            :fields="fields"
            :sortOrder="sortOrder"
            :multiSort=false
            :appendParams="appendParams"
            :withAdvanceFilter=false
            :withCreate="false"
            :withExport="false"
            :withDelete="false"
            :cellClickedFunc="onCellClicked"
            >
                <template slot="additional-control">
                    <button @click="invite" type="button" class="btn btn-primary">
                        <span>INVITE USER</span>
                    </button>
                </template>
            </user-datatable>
        </b-card-body>
    </b-card>
</template>

<script>
    import RequestMixin from 'mixins/requestMixin';
    import DatatableMixin from 'mixins/datatableMixin';

    import UserDatatable from 'components/datatable';

    import InviteUser from 'modals/user/invite';
    import EditUser from 'modals/user/edit';
    import { refreshDatatable } from 'utils/helpers'

    export default {
        mixins: [
            RequestMixin,
            DatatableMixin
        ],
        components: {
            UserDatatable,
        },
        data () {
            const { sort } = this.$route.params;
            const sortOrder = (sort && sort.length > 0) ? sort : [{ field: 'created_at', sortField: 'created_at', direction: 'asc'}];
            return {
                withDatatableControl: true,
                datatableUrl: 'users',
                datatableRef: 'datatableUser',
                pageReference: 'userListPage', 
                fields: [{
                    title: 'Name',
                    name: 'name',
                    sortField: 'name',
                    dataClass: 'cell-clickable',
                    clickable: true,
                },{
                    title: 'Email',
                    name: 'email',
                    sortField: 'email',
                },{
                    title: 'Role',
                    name: 'role',
                    sortField: 'role_id',
                    callback: 'getFromCollection|name',
                },{
                    title: 'Status',
                    name: 'active',
                    sortField: 'active',
                    callback:'formatBoolean|Active,Inactive',
                }],
                sortOrder
            }
        },
        methods: {
            onCellClicked(user, field) {
                if(field.title == 'Name'){
                    this.$modal.show(EditUser, {
                        user,
                    },{
                        height: "auto"
                    });
                }
            },
            invite() {
                this.$modal.show(InviteUser, {
                    inviteEvent: 'invite-user-confirm',
                });
            },
        },
        events: {
            'invite-user-confirm' (refresh) {
                if(refresh) {
                    refreshDatatable(this, this.datatableRef);
                }
            },
            'search-list' (search) {
                this.appendParams.search = search;
                refreshDatatable(this, this.datatableRef);
            },
        }
    }
</script>