<template>
    <div class="w-3/6 shadow-2xl bg-white">
        <div class="flex justify-between items-center bg-blue-700 px-4 text-md font-semibold text-gray-200">
            <p>Share this Post</p>

            <p><i class="fas fa-lock"></i></p>
        </div>

        <div class="flex justify-between items-center py-2 mx-4 bg-white border-b-2 border-gray-300 text-xs text-gray-600 font-medium">
            <p class=""><i class="fab fa-creative-commons-share"></i> Share with friends</p>

            <p class=""><i class="fas fa-cogs"></i> Manage sharing settings</p>
        </div>

        <div class="mx-8 mt-8 mb-2">
            <textarea v-model="body" rows="3" placeholder="Write your caption..." class="w-full p-2 border border-gray-500"></textarea>
        </div>

        <div class="flex justify-between items-start px-8 mt-2 mb-8 bg-white">
            <img v-if="post.pictures.picture_count == 0" class="w-24 h-24 object-cover" :src="'/storage/' + post.posted_by.profile_image.path" alt="Profile Image">
            <img v-else class="w-24 h-24 object-cover" :src="'/storage/' + post.pictures.data[0].path" alt="Profile Image">

            <div class="flex-auto mx-4">
                <p class="text-sm font-bold text-blue-700">{{post.posted_by.name}}</p>
                <p class="text-xs text-gray-600">{{post.created_at}}</p>
                <p class="mt-2 text-sm font-medium">{{post.body}}</p>
            </div>
        </div>

        <div class="flex justify-end items-center bg-gray-200 px-4 text-sm">
            <button @click="dispatchSharePost(post.id)" class="px-2 py-1 m-2 bg-blue-700 text-white font-semibold rounded shadow-lg">Share Post</button>

            <button @click="changeShareMode" class="px-2 py-1 m-2 bg-gray-200 text-gray-800 font-semibold rounded border border-gray-500 shadow-lg">Cancel</button>
        </div>
    </div>
</template>

<script>
    import _ from "lodash";

    export default {
        name: "ShareCard",

        props: ['post'],

        computed: {
            body: {
                get() {
                    return this.$store.getters.body;
                },
                //_.debounce (function is to make sure the form is not updated after every character that user types.
                set: _.debounce(function (body) {
                    return this.$store.commit('setPostBody', body);
                }, 1000)
            }
        },

        methods: {
            changeShareMode() {
                EventBus.$emit('changingShareMode')
            },

            dispatchSharePost(repost_id) {
                this.$store.dispatch('sharePost', repost_id)

                this.changeShareMode()
            },
        }

    }
</script>

<style scoped>

</style>
