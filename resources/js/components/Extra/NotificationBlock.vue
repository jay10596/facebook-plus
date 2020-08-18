<template>
    <div>
        <div class="flex justify-between text-xs p-2 border-b border-gray-400">
            <p class="text-gray-800 font-semibold">Notifications</p>

            <div class="flex text-blue-700 font-medium">
                <p class="px-4">Mark all as read</p>
                <p>Settings</p>
            </div>
        </div>

        <div class="h-80 overflow-y-scroll">
            <div v-for="notification in allNotifications" :key="notification.id" class="border-b border-gray-300">
                <NotificationCard :notification="notification" />
            </div>
        </div>

        <div class="flex justify-center bg-gray-200 text-xs p-2">
            <p class="text-blue-700 font-semibold">See All Notifications</p>
        </div>
    </div>
</template>

<script>
    import NotificationCard from "./NotificationCard";
    import {mapGetters} from "vuex";
    export default {
        name: "NotificationBlock",

        components: {NotificationCard},

        computed: {
            ...mapGetters({
                allNotifications: 'allNotifications',
            }),
        },

        created() {
            this.$store.dispatch('fetchAllNotifications')
        }
    }
</script>

<style scoped>

</style>
