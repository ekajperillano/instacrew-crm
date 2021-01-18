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
                :cellClickedFunc="onCellClicked"
                withControl
                withCreate
                :createFunc="createFunc"
            >
            </user-datatable>
        </b-card-body>
    </b-card>
</template>

<script>
    import RequestMixin from 'mixins/requestMixin';
    import DatatableMixin from 'mixins/datatableMixin';

    import UserDatatable from 'components/datatable';

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
                datatableUrl: 'client',
                datatableRef: 'datatableClient',
                pageReference: 'clientListPage', 
                fields: [{
                    title: 'Name',
                    name: 'name',
                    sortField: 'name',
                    dataClass: 'cell-clickable',
                    clickable: true,
                },{
                    title: 'Address',
                    name: 'address',
                    sortField: 'address',
                },{
                    title: 'Type',
                    name: 'type',
                    sortField: 'type',
                    callback: 'allCap',
                },{
                    title: 'Status',
                    name: 'active',
                    sortField: 'active',
                    callback: 'formatBoolean|Active,Inactive',
                }],
                sortOrder
            }
        },
        methods: {
            onCellClicked(client, field) {
                if(field.title == 'Name'){
                    this.$router.push({
                        name: 'client_detail',
                        params: {
                            id: client.id,
                            listParams: {
                                sort: this.sortOrder,
                                append: this.appendParams,
                            }
                        }
                    });
                }
            },
            createFunc() {
                this.$router.push({
                    name: 'client_detail',
                    params: {
                        id: 'create',
                    }
                });
            }
        },
        events: {
            'search-list' (search) {
                this.appendParams.search = search;
                refreshDatatable(this, this.datatableRef);
            },
        }
    }
</script>