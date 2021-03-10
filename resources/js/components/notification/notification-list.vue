<template>
    <b-card class="mr-0" title="Notifications">
        <div @scroll="onScroll" class="notification-list" ref="notificationList">
            <b-card-body v-for="(notification, index) in notifications" :key="notification.id"
                :class="notificationClass(notification)" >
                <b-card-text @click="view(notification, index)">
                    {{ notification.data.message || 'No Title' }}
                </b-card-text>
                <small class="float-right">
                    {{ formatDate(notification.created_at) }}
                </small>
            </b-card-body>
        </div>
    </b-card>
</template>
<script>
    import { errorToast } from 'utils/components';
    import { formatDate } from 'utils/helpers';
    import RequestMixin from 'mixins/requestMixin';

    export default {
        mixins: [
            RequestMixin
        ],
        data() {
            return {
                notifications: []
            }
        },
        async mounted() {
            if(this.$auth.user()) {
                this.fetchNotifications();
            }
        },
        methods: {
            formatDate,
            async fetchNotifications(since_id = null) {
                let [res, err] = await this.api.get('users/' + this.$auth.user().id + '/notification', {
                    since_id
                });

                if(res) {
                    this.notifications = res
                }

                if(err) {
                    errorToast(this, err);
                }
            },
            onScroll ({ target: { scrollTop, clientHeight, scrollHeight }}) {
                if (scrollTop + clientHeight >= scrollHeight) {
                    // this.fetchNotifications(this.notifications.length);
                }
            },
            notificationClass(notification) {
                if(notification && notification.data && notification.data.type) {
                    return 'notification-item py-2 pl-2 text-truncate ' + notification.data.type;
                }

                return 'notification-item py-2 pl-2 text-truncate';
            },
        }
    }
</script>