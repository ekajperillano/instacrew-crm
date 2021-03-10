<template>
    <b-card>
        <b-card-body>
            <b-card-group deck>
                <List/>
            </b-card-group>
        </b-card-body>
    </b-card>
</template>
<script>
    import List from 'components/notification/notification-list';
    /**** TODO : IMplement detail */
    import Detail from 'components/notification/notification-detail';

    export default {
        components: {
            List,
            Detail,
        },
    }
</script>