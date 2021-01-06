<template>
    <div class="modal" :ref="pageReference">
        <div class="modal-header">
            <span>Email Account Settings</span>
            <button  @click.prevent="close" class="float-right close-modal btn btn-outline-info" >
                <em class="fa fa-times" aria-hidden="true"/>
            </button>
        </div>
        <div class="modal-body">
            <b-row>
                <b-col>
                    <label>Address</label>
                    <b-input-group>
                        <b-input-group-text class="input-group-text" slot="prepend">
                            <strong>@</strong>
                        </b-input-group-text>
                        <b-input id="email-address" v-model="record.address"/>
                    </b-input-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <label>Password</label>
                    <b-input-group>
                        <b-input type="password" v-model="record.password"/>
                    </b-input-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <label>SMTP Host</label>
                    <b-input-group>
                        <b-input v-model="record.smtp_host"/>
                    </b-input-group>
                </b-col>
                <b-col>
                    <label>SMTP Port</label>
                    <b-input-group>
                        <b-input v-model="record.smtp_port"/>
                    </b-input-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <label>IMAP Host</label>
                    <b-input-group>
                        <b-input v-model="record.imap_host"/>
                    </b-input-group>
                </b-col>
                <b-col>
                    <label>IMAP Port</label>
                    <b-input-group>
                        <b-input v-model="record.imap_port"/>
                    </b-input-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col>
                    <label>Security</label>
                    <b-input-group>
                        <b-input v-model="record.security"/>
                    </b-input-group>
                </b-col>
            </b-row>
        </div>
        <div class="modal-footer"> 
            <button type="button" @click.prevent="save" class="btn btn-info">{{ email_account ? 'Update' : 'Add' }}</button>
        </div>
    </div>
</template>

<script>
    import RequestMixin from 'mixins/requestMixin';
    import ModalMixin from 'mixins/modalMixin';

    import { errorToast, successToast } from 'utils/components';

    export default {
        mixins: [
            RequestMixin,
            ModalMixin
        ],
        props: {
            email_account: {
                type: Object,
                required: false
            },
            confirmEvent: {
                default: 'update-create-email-account'
            }
        },
        mounted() {
            if(this.email_account) {
                this.record = this.email_account;
            }
        },
        data() {
            return {
                pageReference: 'updateCreateEmailModal',
                record : {
                    address: null,
                    password: null,
                    smtp_host: null,
                    smtp_port: null,
                    imap_host: null,
                    imap_port: null,
                    security: 'ssl',
                },
            }
        }, 
        methods: {
            async save() {
                const record = this.record;
                
                const [res, err]  =  (this.email_account && this.email_account.id) 
                    ? await this.api.patch(`user-email/${this.email_account.id}`, record)
                    : await this.api.post(`user-email`, record);
                
                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    successToast(this, 'Email Account Saved.')
                    this.$emit('close');
                    this.$events.fire(this.confirmEvent);
                }
            }   
        }
    }
</script>


