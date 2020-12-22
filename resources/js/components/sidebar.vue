<template>
    <sidebar-menu
        :menu="menu"
        :collapsed="collapsed"
        :theme="selectedTheme"
        :show-one-child="true"
        @toggle-collapse="onToggleCollapse"
    >
    </sidebar-menu>
</template>

<script>
    import { SidebarMenu } from 'vue-sidebar-menu';

    const separator = {
        template: `<hr style="border-color: rgba(0, 0, 0, 0.1); margin: 20px;">`
    }

    const logoImg = {
        template: `<div class="py-3">
                <img class="center-img sidebar-logo" src="images/instacrew-logo.png">
        </div>`
    }
    
    export default {
        components: {
            SidebarMenu
        },
        data () {
            return {
                menu: [
                    {
                        component: logoImg,
                        hiddenOnCollapse: true
                    },
                    {
                        component: separator
                    },
                    {
                        href: '/dashboard',
                        title: 'Dashboard',
                        icon: 'fa fa-tachometer'
                    },
                    {
                        href: '/clients',
                        title: 'Clients',
                        icon: 'fa fa-briefcase'
                    },
                    {
                        component: separator
                    },
                    {
                        header: true,
                        title: 'Admin',
                        hiddenOnCollapse: true
                    },
                    {
                        href: '/users',
                        title: 'User Accounts',
                        icon: 'fa fa-users'
                    },
                    {
                        href: '/roles',
                        title: 'Roles and Permissions',
                        icon: 'fa fa-lock'
                    },
                    {
                        component: separator
                    },
                    {
                        header: true,
                        title: 'Settings',
                        hiddenOnCollapse: true
                    },
                    {
                        href: '/profile',
                        title: 'Profile',
                        icon: 'fa fa-user'
                    },
                    {
                        href: '/notifications',
                        title: 'Notifications',
                        icon: 'fa fa-bell'
                    },
                ],
                collapsed: false,
                selectedTheme: 'white-theme',
                isOnMobile: false
            }
        },
        mounted () {
            this.onResize()
            window.addEventListener('resize', this.onResize)
        },
        methods: {
            onToggleCollapse (collapsed) {
                this.collapsed = collapsed
                this.$emit('toggleExpand');
            },
            onResize () {
                if (window.innerWidth <= 767) {
                    this.isOnMobile = true
                    this.collapsed = true
                    this.$emit('toggleExpand');
                } else {
                    this.isOnMobile = false
                    this.collapsed = false
                }
            }
        }
    }
</script>