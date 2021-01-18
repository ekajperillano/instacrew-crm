<template>
    <b-card :ref="pageReference" class="vld-parent client-detail-card">
        <b-card-header> 
            <h4>Socials</h4>
            <div class="right">
                <b-button :disabled="client.active != 1"  @click="openCreateUpdateModal()" variant="primary">Add New Social Media Account</b-button>
            </div>
        </b-card-header>
        <b-card-body> 
               
        </b-card-body>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';

    import { errorToast, successToast } from 'utils/components';

    export default {
        mixins:[
            RequestMixin,
        ],
        mounted() {

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
            }
        },
        methods: {
            openCreateUpdateModal() {
                
            }
        }
    }
</script>