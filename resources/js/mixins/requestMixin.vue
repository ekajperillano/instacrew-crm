<script>
    import request from 'utils/request.js';

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