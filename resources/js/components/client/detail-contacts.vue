<template>
    <b-card :ref="pageReference" class="vld-parent client-detail-card">
        <b-card-header> 
            <h4>Contacts</h4>
        </b-card-header>
        <b-card-body> 
               
        </b-card-body>
        <b-card-footer>

        </b-card-footer>
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
        data() {
            return {
                pageReference: 'clientDetailContact',
            }
        },
    }
</script>