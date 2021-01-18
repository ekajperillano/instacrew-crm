<template>
    <b-card :ref="pageReference" class="vld-parent client-detail-card">
        <b-card-header> 
            <h4>Basic Information</h4>
        </b-card-header>
        <b-card-body> 
               <b-form>
                    <b-form-group label="Name">
                        <b-form-input
                            v-model="record.name"
                            type="text"
                            required
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group label="Address">
                        <b-form-textarea
                            v-model="record.address"
                            type="text"
                            required
                        ></b-form-textarea>
                    </b-form-group>
                </b-form>
        </b-card-body>
        <b-card-footer>
            <b-button @click="onSubmit" variant="primary">{{ (record.id) ? 'Update' : 'Create'}}</b-button>
            <fragment v-if="record.id">
                <b-button v-if="record.type == 'prospect'" @click="setAsCrew" variant="primary">Set as CREW</b-button>
                <b-button 
                    @click="setStatus" 
                    :variant="(record.active == 1) 
                        ? 'danger'
                        : 'success'
                    "> {{
                        (record.active == 1) 
                        ? 'Set as INACTIVE'
                        : 'Set as ACTIVE'
                    }}
                    </b-button>
            </fragment>
        </b-card-footer>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';

    import Confirmation from 'modals/common/confirmation';

    import { errorToast, successToast } from 'utils/components';

    export default {
        mixins:[
            RequestMixin,
        ],
        async mounted() {
            const { params } = this.$route;
            const { id } = params;
            console.log(this.$route);
            if(id && id != 'create') {
                const [res, err] = await this.api.get(`client/${id}`);
                if(res) {
                    this.$emit('on-fetch', res);
                    this.record = res;
                }

                if(err) {
                    errorToast(this, err);
                }
            }
        },
        data() {
            return {
                pageReference: 'clientDetailSocial',
                record: {
                    name: '',
                    address: ''

                }
            }
        },
        methods: {
            async onSubmit() {
                const record = this.record;
                const { id } = record;

                const [res, err]  =  (id) 
                    ? await this.api.patch(`client/${id}`, record)
                    : await this.api.post(`client`, record);
                
                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Client saved.');
                    if(!id) {
                        this.record = res;
                        this.$router.push({name: 'client_detail',  params: { id: res.id }});
                        this.$emit('on-fetch', res);
                    }
                }
            },
            setAsCrew() {
                if(this.record) {
                    const { type }  = this.record;
                    if(type == 'prospect') {
                        this.$modal.show(Confirmation, {
                            question: 'Are you sure you want to set this client as CREW?',
                            confirmEvent: 'confirm-set-as-crew'
                        }, { height: "auto" });
                    }
                }
            },
            async setStatus() {
                if(this.record) {
                    this.$modal.show(Confirmation, {
                        question: (this.record.active == 1) 
                            ? 'Are you sure you want to set this client as INACTIVE?'
                            : 'Are you sure you want to set this client as ACTIVE?',
                        confirmEvent: 'confirm-update-status'
                    }, { height: "auto" });
                }
            }
        },
        events: {
            async 'confirm-set-as-crew' () {
                const { id, type }  = this.record;
                const [res, err] = await this.api.patch(`client/${id}/crew`);

                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Prospect set as crew.')
                    this.record = res;
                }
            },
            async 'confirm-update-status' () {
                const { id, active }  = this.record;
                const endpoint = (active == 1) 
                    ? `client/${id}/inactive`
                    : `client/${id}/active`;

                const [res, err] = await this.api.patch(endpoint);

                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(
                        this,
                        active == 1
                            ? 'Client set as inactive.'
                            : 'Client set as active.'
                    )
                    this.record = res;
                    this.$emit('on-fetch', res);
                }
            }
        }
    }
</script>