<template>
    <div class="modal" :ref="pageReference">
        <div class="modal-header">
            <span>Contact Details</span>
            <button  @click.prevent="close" class="float-right close-modal btn btn-outline-info" >
                <em class="fa fa-times" aria-hidden="true"/>
            </button>
        </div>
        <div class="modal-body">
            <b-row>
                <b-col>
                    <label>Type</label>
                    <v-select
                        placeholder="contact type"
                        v-model="record.type"
                        :options="options.type"
                    />
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <label>Value</label>
                    <b-input-group>
                        <b-input v-model="record.value"/>
                    </b-input-group>
                </b-col>
            </b-row>
        </div>
        <div class="modal-footer"> 
            <button type="button" @click.prevent="save" class="btn btn-info">{{ contact ? 'Update' : 'Add' }}</button>
        </div>
    </div>
</template>

<script>
    import RequestMixin from 'mixins/requestMixin';
    import ModalMixin from 'mixins/modalMixin';

	import vSelect from 'vue-select';

    import { errorToast, successToast } from 'utils/components';

    export default {
        mixins: [
            RequestMixin,
            ModalMixin
        ],
        props: {
            client_id: {
                required: true
            },
            contact: {
                type: Object,
                required: false
            }, 
            type: {
                type: Object,
                required: false
            },
            confirmEvent: {
                default: 'update-create-client-contact'
            }
        },
        components: {
            vSelect
        },
        mounted() {
            if(this.contact) {
                this.record = this.contact;
                if(this.type) {
                    this.record.type = this.type;
                }
            }
        },
        data() {
            return {
                pageReference: 'updateCreateContactModal',
                record : {
                    value: null,
                    type: null,
                },
                options: {
                    type: [{
                        label: 'Email',
                        value: 'email'
                    },{
                        label: 'Mobile',
                        value: 'mobile'
                    },{
                        label: 'Phone',
                        value: 'phone'
                    }]
                }
            }
        }, 
        methods: {
            async save() {
                const { value, type } = this.record;

                const record = {
                    value,
                    key: type ? type.value : null,
                    label: type ? type.label : null,
                };

                const [res, err]  =  (this.contact && this.contact.id) 
                    ? await this.api.patch(`contact/${this.contact.id}`, {
                        ...record,
                        client_id: this.client_id,
                    })
                    : await this.api.post(`contact`, {
                        ...record,
                        client_id: this.client_id,
                    });
                
                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Contact Saved.')
                    this.$emit('close');
                    this.$events.fire(this.confirmEvent, res);
                }
            }   
        }
    }
</script>