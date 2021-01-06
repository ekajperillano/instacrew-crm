<template>
    <b-card  :ref="pageReference" class="profile-card">
        <b-card-header>
            <h4>Linked Email Accounts</h4>
            <div class="right">
                <b-button  @click="openEmailModal()" variant="primary">Add New Email Account</b-button>
            </div>
        </b-card-header>
        <b-card-body>
            <b-table v-if="emails.length > 0" 
                hover
                sticky-header
                :items="emails"
                :fields="email_table.fields"
                responsive="sm"
            >
                <template v-slot:cell(setting)="row">
                    <div>
                        <b-button variant="info" @click="openEmailModal(row.item)" >Update</b-button>
                        <b-button variant="danger" @click="removeEmail(row.item)" >Remove</b-button>
                    </div>
                </template>
            </b-table>
        </b-card-body>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';

    import AddEmailAccount from 'modals/user-email/update-create';
    import Confirmation from 'modals/common/confirmation';

    import { errorToast, successToast } from 'utils/components';

    export default {
        mixins: [
            RequestMixin
        ],
        mounted() {
            if(this.$auth.check()) {
                this.user = this.$auth.user();
                this.getAccounts();
            }
        },
        data() {
            return {
                pageReference: 'profileEmailAccount',
                user: null,
                emails: [],
                email_table : {
                    fields: [{
                        label: 'Email Address',
                        thClass: 'text-center',
                        tdClass: 'center-text',
                        key: 'address'
                    },{
                        label: 'Settings',
                        thClass: 'text-center',
                        tdClass: 'center-text',
                        key: 'setting'
                    }]
                },
            }
        },
        methods: {
            async getAccounts() {
                const { id: userId } = this.user;

                const [res, err] = await this.api.get(`user-email/${userId}/user`);

                if(res) {
                    this.emails = res;
                }

                if(err) {
                    errorToast(this, err);
                }

            },
            openEmailModal(email_account) {
                console.log(email_account);
                this.$modal.show(AddEmailAccount, {
                    confirmEvent: 'update-create-email-account',
                    email_account
                }, {
                    height: "auto"
                });
            },
            removeEmail(email_account) {
                this.$modal.show(Confirmation, {
                    question: 'Are you sure you want to remove this email account',
                    confirmEvent: 'confirm-user-email-remove',
                    modal_data: email_account
                });
            }
        },
        events: {
            'update-create-email-account' () {
                this.getAccounts()
            },
            async 'confirm-user-email-remove'(email_account) {
                if(email_account) {
                    const { id } = email_account
                    const [res, err ] = await this.api.delete(`user-email/${id}`);

                    if(res) {
                        successToast(this, 'Email account removed.');
                        this.getAccounts();
                    }

                    if(err) {
                        errorToast(this, err);
                    }
                }
            }
        }
    }
</script>