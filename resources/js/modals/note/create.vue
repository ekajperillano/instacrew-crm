<template>
    <div class="modal" :ref="pageReference">
        <div class="modal-header">
            <span>Note Details</span>
            <button  @click.prevent="close" class="float-right close-modal btn btn-outline-info" >
                <em class="fa fa-times" aria-hidden="true"/>
            </button>
        </div>
        <div class="modal-body">
            <b-row>
                <b-col>
                    <label>Note</label>
                    <b-input-group>
                        <b-textarea
                            v-model="record.note"
                            type="text"
                            required
                        ></b-textarea>
                    </b-input-group>
                </b-col>
            </b-row>
        </div>
        <div class="modal-footer"> 
            <button type="button" @click.prevent="save" class="btn btn-info">{{ 'Add' }}</button>
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
            note: {
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
            if(this.note) {
                this.record = this.note;
            }
        },
        data() {
            return {
                pageReference: 'updateCreateNoteModal',
                record : {
                    note: null
                },
            }
        }, 
        methods: {
            async save() {
                const { note } = this.record;

                const record = {
                    note,
                };

                const [res, err] = await this.api.post(`note`, {
                    ...record,
                    client_id: this.client_id,
                });
                
                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Note Saved.')
                    this.$emit('close');
                    this.$events.fire(this.confirmEvent, res);
                }
            }   
        }
    }
</script>