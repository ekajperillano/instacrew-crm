<template>
    <b-card :ref="pageReference" class="vld-parent client-detail-card">
        <b-card-header> 
            <h4>Contacts</h4>
            <div class="right">
                <b-button :disabled="client.active != 1"  @click="openCreateUpdateModal()" variant="primary">Add New Contact Information</b-button>
            </div>
        </b-card-header>
        <b-card-body> 
            <b-list-group>
                <b-list-group-item class="align-items-center d-flex" v-for="(contact, index) in contacts" :key="index" >
                    <b-link :href="getLink(contact)"> 
                        <b-icon class="mr-2" :icon="getIcon(contact)"></b-icon> {{ contact.value }}
                    </b-link>
                    <div class="right">
                        <b-button @click="openEditModal(contact)" :disabled="client.active != 1" variant="primary"><b-icon icon="pencil"/></b-button>
                        <b-button @click="confirmDelete(contact.id, index)" :disabled="client.active != 1" variant="danger"><b-icon icon="trash"/></b-button> 
                    </div>
                </b-list-group-item>
            </b-list-group>
        </b-card-body>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';

    import { errorToast, successToast } from 'utils/components';

    import AddClientContact from 'modals/contact/update-create';
    import Confirmation from 'modals/common/confirmation';

    export default {
        mixins:[
            RequestMixin,
        ],
        mounted() {
            const { contacts } = this.client;
            if(contacts && contacts.length > 0) {
                this.contacts = contacts;
            }
        },
        props: {
            client: {
                required: false,
                default: null
            }
        },
        data() {
            return {
                pageReference: 'clientDetailContact',
                contacts: [],
            }
        },
        methods: {
            openCreateUpdateModal() {
                this.$modal.show(AddClientContact, {
                    client_id: this.client.id,
                    confirmEvent: 'create-client-contact',
                }, {
                    height: "auto"
                });
            },
            openEditModal(contact) {
                this.$modal.show(AddClientContact, {
                    contact,
                    type: {
                        label: contact.label,
                        value: contact.key
                    },
                    client_id: this.client.id,
                    confirmEvent: 'edit-client-contact',
                }, {
                    height: "auto"
                });
            },
            confirmDelete(id, index) {
                this.$modal.show(Confirmation, {
                    question: 'Are you sure you remove this contact account?',
                    modal_data: {
                        id,
                        index
                    },
                    confirmEvent: 'delete-client-contact'
                }, { height: "auto" });
            },
            getIcon(contact) {
                switch (contact.key) {
                    case 'email':
                        return 'envelope-fill';
                    case 'phone':
                        return 'telephone-fill';
                    case 'mobile':
                        return 'phone-fill';
                    default:
                        return '';
                }
            },
            getLink(contact) {
                const {value, key } = contact;

                switch (key) {
                    case 'email':
                        return `mailto:${value}`;
                    case 'phone':
                    case 'mobile':
                        return `tel:${value}`;
                    default:
                        return '';
                }
            }
        },
        events: {
            'create-client-contact' (contact) {
                if(contact) {
                    this.contacts.push(contact)
                }
            },
            'edit-client-contact' (account) {

            },
            async 'delete-client-contact' (data) {
                const { id, index } = data;

                const [res, err] = await this.api.delete(`contact/${id}`);

                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    this.contacts.splice(index, 1);
                    successToast(this, 'Contact removed.');
                }
            }
        }
    }
</script>