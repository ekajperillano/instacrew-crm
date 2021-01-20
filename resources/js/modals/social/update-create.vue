<template>
    <div class="modal" :ref="pageReference">
        <div class="modal-header">
            <span>Social Details</span>
            <button  @click.prevent="close" class="float-right close-modal btn btn-outline-info" >
                <em class="fa fa-times" aria-hidden="true"/>
            </button>
        </div>
        <div class="modal-body">
            <b-row>
                <b-col>
                    <label>Url</label>
                    <b-input-group>
                        <b-input id="email-address" v-model="record.url"/>
                    </b-input-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <label>Type</label>
                    <v-select
                        simpleValue
                        placeholder="social type"
                        v-model="record.type"
                        :options="options.type"
                        :reduce="type => type.value"
                    />
                </b-col>
            </b-row>
        </div>
        <div class="modal-footer"> 
            <button type="button" @click.prevent="save" class="btn btn-info">{{ social ? 'Update' : 'Add' }}</button>
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
            social: {
                type: Object,
                required: false
            },
            confirmEvent: {
                default: 'update-create-social-account'
            }
        },
        components: {
            vSelect
        },
        mounted() {
            if(this.social) {
                this.record = this.social;
            }
        },
        data() {
            return {
                pageReference: 'updateCreateSocialModal',
                record : {
                    url: null,
                    type: null,
                },
                options: {
                    type: [{
                        label: 'Facebook',
                        value: 'facebook'
                    },{
                        label: 'Instagram',
                        value: 'instagram'
                    },{
                        label: 'Twitter',
                        value: 'twitter'
                    }]
                }
            }
        }, 
        methods: {
            async save() {
                const record = this.record;
                
                const [res, err]  =  (this.social && this.social.id) 
                    ? await this.api.patch(`social/${this.social.id}`, record)
                    : await this.api.post(`social`, {
                        ...record,
                        client_id: this.client_id
                    });
                
                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Social Account Saved.')
                    this.$emit('close');
                    this.$events.fire(this.confirmEvent, res);
                }
            }   
        }
    }
</script>