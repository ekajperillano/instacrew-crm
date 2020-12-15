<template>
    <div id="main">
        <crm-header :expanded="expanded" />
        <sidebar v-if="$auth.check()" @toggleExpand="toggleExpand"/>
        <div id="page-content" :class="expanded == true ? 'expanded' : 'minimized' ">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    import sidebar from 'components/sidebar.vue'
    import header from 'components/header.vue'

    export default {
        components: {
            sidebar,
            crmHeader: header
        },
        data() {
            return {
                expanded: true,
            }
        },
        methods: {
            toggleExpand() {
                const { expanded } = this;

                this.expanded = !expanded;
            }
        }
    }
</script>