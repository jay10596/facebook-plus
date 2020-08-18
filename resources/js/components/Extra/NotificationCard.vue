<template>
    <div :class="notificationClass">
        <div @click="dispatchMarkAsRead" class="flex justify-between items-center">
            <img class="w-12 h-12 object-cover rounded-full" :src="'/storage/' + notification.user.profile_image.path" alt="Profile Image">

            <div class="flex-auto mx-2">
                <p class="h-8 overflow-y-hidden text-xs font-normal text-gray-800"><span class="font-bold text-blue-700">{{notification.user.name}}</span> {{notification.message}} "{{notification.content.body}}"</p>

                <p class="mt-1 text-xs text-gray-500">
                    <i v-if="notification.type=='CommentNotification'" class="fas fa-comment-alt text-green-500"></i>
                    <i v-if="notification.type=='FriendNotification'" class="fas fa-user-check text-gray-700"></i>
                    <i v-if="notification.type=='LikeNotification'" class="fas fa-thumbs-up text-blue-500"></i>
                    <i v-if="notification.type=='ShareNotification'" class="fas fa-retweet text-gray-500"></i>

                    {{notification.created_at}}
                </p>
            </div>
        </div>

        <div v-if="notification.content == 'sent'" class="flex ml-12 mt-2 text-xs">
            <button @click="acceptFriendRequest" class="ml-2 py-1 px-3 bg-blue-700 mr-2 shadow-xl border text-white font-semibold">
                <i class="fas fa-user-check"></i> Accept
            </button>

            <button @click="deleteFriendRequest" class="py-1 px-3 bg-white mr-2 shadow-xl text-gray-800 border border-gray-400 font-semibold">
                <i class="fas fa-user-times"></i> Delete
            </button>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "NotificationCard",

        props: ['notification'],

        computed: {
            ...mapGetters({
                unreadNotifications: 'unreadNotifications',
            }),

            notificationMode: {
                get() {
                    return this.$store.getters.notificationMode;
                },
                set(notificationMode) {
                    return this.$store.commit('setNotificationMode', notificationMode);
                }
            },

            notificationClass() {
                var i, unreadNotificationsIDs = []

                for (i = 0; i < this.unreadNotifications.length; i++) {
                    unreadNotificationsIDs.push(this.unreadNotifications[i].id)
                }

                if(unreadNotificationsIDs.includes(this.notification.id)) {
                    return 'py-3 px-3 bg-gray-100 hover:bg-gray-200'
                }
                return 'py-3 px-3 bg-white hover:bg-gray-200'
            }
        },

        methods: {
            dispatchMarkAsRead() {
                this.$store.dispatch('markAsRead', this.notification)
            },

            acceptFriendRequest() {
                this.notification.content = ''
                this.notification.message = ': Friend request accepted'
                this.$store.dispatch('acceptRequest', this.notification.user.id)
                this.$store.dispatch('hideFriendButtons', {id: this.notification.id, content: 'accepted', message: ': Friend request is accepted'})
            },

            deleteFriendRequest() {
                this.notification.content = ''
                this.notification.message = ': Friend request deleted'
                this.$store.dispatch('deleteRequest', this.notification.user.id)
                this.$store.dispatch('hideFriendButtons', {id: this.notification.id, content: 'deleted', message: ': Friend request is deleted'})
            }
        }
    }
</script>

<style scoped>

</style>
