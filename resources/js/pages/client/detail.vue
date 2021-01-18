<template>
    <fragment>
        <basic @on-fetch="onBasicMount"/>
        <fragment v-if="record && (record.id) ">
            <socials :client="record" />    
            <contacts/>
        </fragment>
    </fragment>
</template>
<script>
    import Basic from 'components/client/detail-basic';
    import Socials from 'components/client/detail-socials';
    import Contacts from 'components/client/detail-contacts';

    export default {
        components: {
            Basic,
            Socials,
            Contacts
        },
        data() {
            return {
                record: null
            }
        },
        methods: {
            onBasicMount(record) {
                if(record) {
                    this.record = record
                }
            }
        }
    }
</script>