<template>
    <b-card class="mr-0" title="Notifications">
        <div @scroll="onScroll" class="notification-list" ref="notificationList">
            <b-card-body v-for="(notification, index) in notifications" :key="notification.id"
                :class="[
                    notificationClass(notification),
                ]" >
                <b-row>
                    <b-col class="icon-container" sm="1">
                        <b-icon class="icon" :icon="(notification.read_at) ? 'envelope-open' : 'envelope-fill'"></b-icon>
                    </b-col>
                    <b-col class="body-container">
                        <b-card-text @click="view(notification, index)">
                            <h5 class="title">{{ notification.data.title || 'No Title' }} </h5>
                            <b-row>
                                <b-col cols="10" class="message">{{ notification.data.message || 'N/A' }} </b-col>
                                <b-col class="float-right">
                                    <small>
                                        {{ formatDate(notification.created_at) }}
                                    </small>
                                </b-col>
                            </b-row>
                        </b-card-text>
                    </b-col>
                </b-row>
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
                    return 'notification-item py-2 pl-2 ' + notification.data.type;
                }

                return 'notification-item py-2 pl-2 ';
            },
            view(notification, index) {

            }
        }
    }
</script>