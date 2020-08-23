<template>
    <div class="flex justify-between">
        <div :class="commentClass">
            <img class="w-8 h-8 object-cover rounded-full" :src="'/storage/' + comment.commented_by.profile_image.path" alt="Profile Image">

            <div>
                <div class="flex-auto ml-2 bg-gray-200 rounded-lg p-2 text-sm">
                    <router-link :to="'/users/' + comment.commented_by.id" class="font-bold text-blue-700">
                        {{comment.commented_by.name}}
                    </router-link>

                    <p v-if="! commentEditMode && ! comment.tag.taggedUserName" class="inline">{{comment.body}}</p>

                    <p v-else-if="! commentEditMode && comment.tag.taggedUserName">{{comment.tag.newBody[0]}}<router-link :to="'/users/' + comment.tag.taggedUserID" class="text-blue-700 font-semibold">{{comment.tag.taggedUserName}}</router-link> {{comment.tag.newBody[1]}}</p>

                    <div v-else class="relative inline ml-2">
                        <input v-model="comment.body" @input="checkTags(comment.body)" class="outline-none px-2 border border-gray-400"></input>

                        <div v-if="tagMode" @click="tagMode = false" class="fixed right-0 left-0 top-0 bottom-0"></div>

                        <div v-if="tagMode" class="absolute bg-white w-56 mt-4 top-0 text-xs shadow-2xl z-20 border border-gray-300">
                            <div v-for="user in searchResult" :key='user.id'>
                                <button @click="tagUser(user), tagMode = false" class="flex w-full items-center p-2 text-gray-800 font-semibold border-b border-gray-200 hover:bg-blue-700 hover:text-white">
                                    <img class="w-8 h-8 object-cover" :src="'/storage/' + user.profile_image.path" alt="Profile Image">

                                    <p class="mx-2">{{user.name}}</p>
                                </button>
                            </div>
                        </div>

                        <button @click="dispatchEditComment(comment.id, comment_index, comment.body, comment.post_id, post_index), commentEditMode = false" class="ml-2 text-gray-700 focus:outline-none"><i class="fas fa-check-circle"></i></button>

                        <button @click="commentEditMode = false, comment.body = orginalCommentBody" class="ml-2 text-gray-700 focus:outline-none"><i class="fas fa-ban"></i></button>
                    </div>
                </div>

                <div v-if="comment.gif">
                    <img :src="'/storage/' + comment.gif" class="ml-2 p-2">
                </div>

                <div class="relative flex justify-between text-xs">
                    <div class="flex w-full">
                        <div v-if="favouriteMode" class="absolute">
                            <div class="flex justify-center items-center bg-white border shadow-2xl rounded-l-full rounded-r-full text-lg -mt-8">
                                <button @click="dispatchFavouriteComment(comment.id, comment_index, comment.post_id, post_index, 1), favouriteMode = ! favouriteMode" class="mx-2">❤️</button>
                                <button @click="dispatchFavouriteComment(comment.id, comment_index, comment.post_id, post_index, 2), favouriteMode = ! favouriteMode" class="mx-2">😝</button>
                                <button @click="dispatchFavouriteComment(comment.id, comment_index, comment.post_id, post_index, 3), favouriteMode = ! favouriteMode" class="mx-2">😢</button>
                            </div>
                        </div>

                        <button v-if="! comment.user_favourited" @click="favouriteMode = ! favouriteMode" class="ml-4 font-medium text-blue-700 hover:font-semibold focus:outline-none">Like</button>
                        <button v-if="comment.user_favourited && comment.favourited_type == 1" @click="favouriteMode = ! favouriteMode" class="ml-4 font-medium text-blue-700 hover:font-semibold focus:outline-none">❤</button>
                        <button v-if="comment.user_favourited && comment.favourited_type == 2" @click="favouriteMode = ! favouriteMode" class="ml-4 font-medium text-blue-700 hover:font-semibold focus:outline-none">😝</button>
                        <button v-if="comment.user_favourited && comment.favourited_type == 3" @click="favouriteMode = ! favouriteMode" class="ml-4 font-medium text-blue-700 hover:font-semibold focus:outline-none">😢</button>

                        <button @click="commentEditMode = ! commentEditMode" class="ml-4 font-medium text-blue-700 hover:font-semibold focus:outline-none">Edit</button>

                        <button @click="dispatchDeleteComment(comment.id, comment_index, comment.post_id, post_index)" class="ml-4 font-medium text-blue-700 hover:font-semibold focus:outline-none">Delete</button>

                        <p class="ml-4 text-xs">{{comment.updated_at}}</p>
                    </div>

                    <div>
                        <div class="flex justify-center items-center bg-white border shadow-2xl rounded-l-full rounded-r-full text-sm -mt-2 px-1">
                            <p>❤️</p>
                            <p>😝</p>
                            <p>😢</p>
                            <p class="ml-2 font-medium text-gray-600">{{comment.favourites.favourite_count}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "CommentCard",

        props: ['comment', 'comment_index', 'post_index'],

        computed: {
            ...mapGetters({
                searchResult: 'searchResult',
            }),

            commentClass() {
                if (this.comment.gif) {
                    return 'flex px-4 py-2'
                }

                return 'flex px-4 py-2 items-center'
            }
        },

        data() {
            return {
                orginalCommentBody: this.comment.body,
                commentEditMode: false,
                gifMode: false,
                favouriteMode: false,
                tagMode: false,
                hasTag: false
            }
        },

        methods: {
            dispatchEditComment(comment_id, comment_index, comment_body, post_id, post_index) {
                this.$store.dispatch('updateComment', {comment_id, comment_index, comment_body, post_id, post_index})
            },

            dispatchDeleteComment(comment_id, comment_index, post_id, post_index) {
                this.$store.dispatch('deleteComment', {comment_id, comment_index, post_id, post_index})
            },

            dispatchFavouriteComment(comment_id, comment_index, post_id, post_index, type) {
                this.$store.dispatch('favouriteUnfavouriteComment', {comment_id, comment_index, post_id, post_index, type})
            },

            checkTags(body) {
                if(body.includes('@') && ! this.hasTag) { //Because we are allowing to use @ only once. Only dispatch result if @ doesn't exist at all.
                    let index = body.indexOf('@')
                    let searchTerm = body.substring(index + 1, index + 2)

                    this.tagMode = true
                    this.$store.dispatch('fetchSearchResult', searchTerm)
                }
            },

            tagUser(user) { //I can pass body from the top as well but then I will have to creat 2 different buttons for editMode true and false which why this is another way to make <template> code look simple
                this.comment.body = this.comment.body.replace('@', `@${user.name} `)
                this.comment.tag.taggedUserName = user.name
                this.comment.tag.taggedUserID = user.id
                this.comment.tag.newBody = this.comment.body.split(user.name);
                this.tagMode = false
                this.hasTag = true
            }
        }
    }
</script>

<style scoped>

</style>
