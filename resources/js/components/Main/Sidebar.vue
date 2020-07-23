<template>
    <div class="w-3/12 p-4 bg-white border-r-2 border-gray-400 shadow-sm">
        <router-link v-for="tab in generalTabs" :key="tab.title" v-if="tab.show" :to="tab.to" class="text-md font-semibold focus:outline-none">
            <div class="flex mb-4">
                <div class="w-8 text-center"><i :class="tab.icon"></i></div> {{tab.title}}
            </div>
        </router-link>

        <div v-if="title == 'Marketplace | Facebook' || title == 'Categories | Facebook'">
            <p class="ml-2 mb-4 text-sm text-gray-700 font-semibold">Categories</p>

            <router-link v-for="tab in categoryTabs" :key="tab.title" :to="tab.to" class="text-sm font-normal focus:outline-none">
                <div class="flex mb-4 items-center">
                    <div :class="tab.circleColor"><i :class="tab.icon"></i></div> {{tab.title}}
                </div>
            </router-link>
        </div>

        <div>
            <button @click="dispatchLogout" class="text-md font-semibold focus:outline-none">
                <div class="flex">
                    <div class="w-8 text-center"><i class="fas fa-sign-out-alt"></i></div> Logout
                </div>
            </button>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "Sidebar",

        computed: {
            ...mapGetters({
                authUser: 'authUser',
                title: 'title'
            })
        },

        data(){
            return {
                categoryMode: false,

                generalTabs: [
                    {to: '/birthday', show: User.loggedIn(), title: 'Birthday', icon: 'fas fa-birthday-cake'},
                    {to: '/edituser', show: User.loggedIn(), title: 'Edit Profile', icon: 'fas fa-user-cog'},
                    {to: '/items', show: User.loggedIn(), title: 'Marketplace', icon: 'fas fa-store-alt'},
                ],

                categoryTabs: [
                    {to: '/category1items', title: 'Housing', circleColor: 'ml-4 mr-2 w-8 h-8 rounded-full bg-blue-500 text-center', icon: 'fas fa-house-user text-white mt-2'},
                    {to: '/category2items', title: 'Electronics', circleColor: 'ml-4 mr-2 w-8 h-8 rounded-full bg-yellow-500 text-center', icon: 'fas fa-mobile-alt text-white mt-2'},
                    {to: '/category3items', title: 'Clothing', circleColor: 'ml-4 mr-2 w-8 h-8 rounded-full bg-red-500 text-center', icon: 'fas fa-tshirt text-white mt-2'},
                    {to: '/category4items', title: 'Entertainment', circleColor: 'ml-4 mr-2 w-8 h-8 rounded-full bg-green-500 text-center', icon: 'fas fa-gamepad text-white mt-2'},
                    {to: '/category5items', title: 'Vehicles & bikes', circleColor: 'ml-4 mr-2 w-8 h-8 rounded-full bg-pink-500 text-center', icon: 'fas fa-car text-white mt-2'},
                ],
            }
        },

        methods: {
            dispatchLogout() {
                this.$store.dispatch('logoutAuthUser')
            }
        }
    }
</script>

<style scoped>

</style>
