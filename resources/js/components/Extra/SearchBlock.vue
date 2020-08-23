<template>
    <div class="relative">
        <div class="absolute mx-2 text-xl text-gray-600"><i class="fas fa-search"></i></div>

        <input @input="dispatchSearchUsers" v-model="searchTerm" @focus="searchMode = true" type="text" class="w-56 h-8 pl-10 text-sm rounded-full bg-gray-200 focus:outline-none focus:outline-none" placeholder="Search...">

        <div v-if="searchMode" @click="searchMode = false" class="fixed right-0 left-0 top-0 bottom-0"></div>

        <div v-if="searchMode" class="absolute bg-white w-56 mt-2 text-xs shadow-2xl z-20 border border-gray-300">
            <div v-if="searchResult.length == 0" class="p-2">No Result found for '{{searchTerm}}'</div>

            <div v-for="user in searchResult" :key='user.id' @click="searchMode = false, searchTerm = ''">
                <router-link :to="'/users/' + user.id" class="flex items-center p-2 border-b border-gray-200 hover:bg-gray-100">
                    <img class="w-8 h-8 object-cover rounded-full" :src="'/storage/' + user.profile_image.path" alt="Profile Image">

                    <p class="mx-2 text-blue-700 font-semibold">{{user.name}}</p>
                </router-link>
            </div>

            <p class="bg-gray-100 p-1 text-center font-medium text-blue-700 border-t border-gray-300">See more users</p>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash';
    import {mapGetters} from "vuex";

    export default {
        name: "SearchBlock",

        computed: {
            ...mapGetters({
                searchResult: 'searchResult',
            })
        },

        data() {
            return {
                searchTerm: '',
                searchMode: false
            }
        },

        methods: {
            dispatchSearchUsers: _.debounce(function (e) {
                if(this.searchTerm.length < 2) {
                    return
                }

                this.$store.dispatch('fetchSearchResult', this.searchTerm)
            }, 500)
        }
    }
</script>

<style scoped>

</style>
