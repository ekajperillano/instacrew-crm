<template>
    <b-card v-if="notitifcation_data" class="ml-0">
        <b-card-header>
            <b-card-title>{{notitifcation_data.title || 'No Tite'}}</b-card-title>
        </b-card-header>
        <b-card-body>
            <b-card-text>
                {{ notitifcation_data.message || 'N/A' }}
            </b-card-text>
        </b-card-body>
    </b-card>
</template>
<script>
    import RequestMixin from 'mixins/requestMixin';

    import { errorToast } from 'utils/components';
    
    export default {
        mixins: [
            RequestMixin
        ],
        async mounted() {
            const { params } = this.$route;
            const { id } = params;
            if(id) {
                const [res, err] = await this.api.get(`notification/${id}/show`);
                if(res) {
                    const { data }  = res;
                    this.notitifcation = res
                    this.notitifcation_data = data
                }
                console.log(err);
                if(err) {
                    errorToast(this, err);
                }
            }
        },
        data() {
            return {
                notitifcation: null,
                notitifcation_data: null
            }
        }
    }
</script>