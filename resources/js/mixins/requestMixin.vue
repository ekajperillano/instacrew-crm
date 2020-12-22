<script>
    import request from 'utils/request';

    export default {
        data() {
            return {
                pageReference: '',
            }
        },
        computed: {
            api: function() {
                return new request(this, this.pageReference);
            }
        },
    }
</script>