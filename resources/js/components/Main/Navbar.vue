<template>
    <div class="flex justify-between items-center bg-blue-700 h-12 border-b border-gray-400 shadow-sm">
        <div class="flex justify-start items-center">
            <router-link to="/" class="ml-12 mr-2 text-4xl text-white">
                <i class="fab fa-facebook"></i>
            </router-link>

            <SearchBlock />
        </div>

        <div class="w-full flex justify-center items-center h-full text-white">
            <router-link to="/" :class="homeButtonClass">
                <i class="fas fa-home"></i>
            </router-link>

            <router-link :to="'/users/' + authUser.id" :class="profileButtonClass">
                <img class="w-8 h-8 object-cover rounded-full" :src="'/storage/' + authUser.profile_image.path" alt="Profile Image">
            </router-link>

            <router-link to="/" class="flex items-center h-full px-6 text-2xl text-white border-b-2 border-blue-700 hover:border-white">
                <i class="fab fa-facebook-messenger"></i>
            </router-link>
        </div>

        <div class="w-1/3 relative flex justify-end mr-8 text-2xl">
            <button @click="notificationMode = ! notificationMode" class="hover:text-white focus:outline-none focus:text-white">
                <i class="fas fa-bell mx-6"></i>
                <div v-if="unreadNotifications.length > 0" class="absolute h-5 w-5 top-0 ml-8 flex items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"><p>{{unreadNotifications.length}}</p></div>
            </button>

            <div v-if="notificationMode" @click="notificationMode = false" class="fixed right-0 left-0 top-0 bottom-0"></div>

            <div v-if="notificationMode" class="absolute right-0 w-96 mr-20 mt-10 shadow-2xl bg-white border-b border-gray-400">
                <NotificationBlock />
            </div>

            <button class="hover:text-white focus:outline-none"><i class="fas fa-cog mx-6"></i></button>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import NotificationBlock from "../Extra/NotificationBlock";
    import SearchBlock from "../Extra/SearchBlock";

    export default {
        name: "Navbar",

        components: {SearchBlock, NotificationBlock},

        computed: {
            ...mapGetters({
                authUser: 'authUser',
                title: 'title',
                unreadNotifications: 'unreadNotifications'
            }),

            notificationMode: {
                get() {
                    return this.$store.getters.notificationMode;
                },
                set(notificationMode) {
                    return this.$store.commit('setNotificationMode', notificationMode);
                }
            },

            homeButtonClass() {
                if(this.title == 'NewsFeed | Facebook') {
                    return 'flex items-center h-full px-6 text-white text-2xl border-b-2 border-white'
                }
                return 'flex items-center h-full px-6 text-2xl text-white border-b-2 border-blue-700 hover:border-white'
            },

            profileButtonClass() {
                if(this.title == 'Profile | Facebook') {
                    return 'flex items-center h-full px-6 text-2xl border-b-2 hover:text-white focus:outline-none'
                }
                return 'flex items-center h-full px-6 text-2xl border-b-2 border-blue-700 hover:border-white'
            }
        },

        created() {//Just to trigger the unreadNotificationCount
            this.$store.dispatch('fetchAllNotifications')
        }
    }
</script>

<style scoped>

</style>
