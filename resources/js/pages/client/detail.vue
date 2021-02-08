<template>
    <fragment>
        <div class="detail-page-control">
            <b-col> 
                  <b-button @click="$router.push({
                        name: 'client_list'
                    })" variant="primary"><b-icon-chevron-double-left/> Back to List </b-button>
            </b-col>
            <b-col class="right"> 
                <b-button v-if="mode == 'update'" @click="createNew()" variant="primary"><b-icon icon="person-plus"/> Create New</b-button>
                <b-button v-if="record" @click="confirmDelete()" variant="danger"><b-icon icon="trash"/> Delete </b-button>
            </b-col>
        </div>
        <basic :key="mode" @on-fetch="onBasicMount"/>
        <fragment v-if="record && (record.id)" >
            <socials :client="record" />    
            <contacts :client="record"/>
            <notes :client="record"/>
        </fragment>
    </fragment>
</template>
<script>
    import Basic from 'components/client/detail-basic';
    import Socials from 'components/client/detail-socials';
    import Contacts from 'components/client/detail-contacts';
    import Notes from 'components/client/detail-note';
    import Confirmation from 'modals/common/confirmation';

    import { errorToast } from 'utils/components';

    import RequestMixin from 'mixins/requestMixin';

    export default {
        mixins: [
            RequestMixin
        ],
        components: {
            Basic,
            Socials,
            Contacts,
            Notes
        },
        data() {
            return {
                record: null,
                pageReference: 'clientDetail',
                mode: 'create'
            }
        },
        methods: {
            onBasicMount(record) {
                if(record) {
                    this.mode = 'update';
                    this.record = record
                }
            },
            createNew() {
                this.record = null;
                this.mode  = 'create';
                this.$router.push({
                    name: 'client_detail',
                    params: {
                        id: 'create',
                    }
                });
            },
            confirmDelete() {
                this.$modal.show(Confirmation, {
                    question: 'Are you sure you delete this client?',
                    modal_data: this.record.id,
                    confirmEvent: 'delete-client'
                }, { height: "auto" });
            },
        },
        events: {
            async 'delete-client' (id) {
                const [res, err] = await this.api.delete(`client/${id}`);

                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    this.$router.push({
                        name: 'client_list'
                    })
                }
            }
        }
    }
</script>