<template>
    <b-card :ref="pageReference" class="vld-parent client-detail-card">
        <b-card-header> 
            <h4>Socials</h4>
            <div class="right">
                <b-button :disabled="client.active != 1"  @click="openCreateUpdateModal()" variant="primary">Add New Social Media Account</b-button>
            </div>
        </b-card-header>
        <b-card-body> 
            <b-list-group>
                <b-list-group-item class="align-items-center d-flex" v-for="(social, index) in socials" :key="index" >
                    <b-link v-linkified:options="{ defaultProtocol: 'https' }" :href="social.url" target="_blank"> 
                        <b-icon class="mr-2" :icon="social.type"></b-icon> {{ social.url }}
                    </b-link>
                    <div class="right">
                        <b-button @click="openEditModal(social)" variant="primary"><b-icon icon="pencil"/></b-button>
                        <b-button @click="confirmDelete(social.id, index)" variant="danger"><b-icon icon="trash"/></b-button> 
                    </div>
                </b-list-group-item>
            </b-list-group>
        </b-card-body>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';
    
    import { errorToast, successToast } from 'utils/components';

    import AddSocialAccount from 'modals/social/update-create';
    import Confirmation from 'modals/common/confirmation';


    export default {
        mixins:[
            RequestMixin,
        ],
        mounted() {
            const { socials } = this.client;
            if(socials && socials.length > 0) {
                this.socials = socials;
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
                pageReference: 'clientDetailSocial',
                socials: [],
            }
        },
        methods: {
            openCreateUpdateModal() {
                this.$modal.show(AddSocialAccount, {
                    client_id: this.client.id,
                    confirmEvent: 'create-social-account',
                }, {
                    height: "auto"
                });
            },
            openEditModal(social) {
                this.$modal.show(AddSocialAccount, {
                    social,
                    client_id: this.client.id,
                    confirmEvent: 'edit-social-account',
                }, {
                    height: "auto"
                });
            },
            confirmDelete(id, index) {
                this.$modal.show(Confirmation, {
                    question: 'Are you sure you remove this social account?',
                    modal_data: {
                        id,
                        index
                    },
                    confirmEvent: 'delete-social-account'
                }, { height: "auto" });
            }
        },
        events: {
            'create-social-account' (account) {
                console.log(account);
                if(account) {
                    this.socials.push(account)
                }
            },
            'edit-social-account' (account) {

            },
            async 'delete-social-account' (data) {
                const { id, index } = data;

                const [res, err] = await this.api.delete(`social/${id}`);

                if(err) {
                    errorToast(this, err);
                }

                if(res) {
                    this.socials.splice(index, 1);
                    successToast(this, 'Social account removed.');
                }
            }
        }
    }
</script>