<template>
    <div class="bg-white rounded mt-4 shadow">
        <div class="p-4">
            <div class="flex justify-between items-center">
                <img class="w-8 h-8 object-cover rounded-full" :src="'/storage/' + post.posted_by.profile_image.path" alt="Profile Image">

                <div v-if="! post.shared_post" class="flex-auto mx-4">
                    <div v-if="! post.posted_on"><p class="text-sm font-bold text-blue-700">{{post.posted_by.name}}</p></div>
                    <div v-else><p class="text-sm font-bold text-blue-700">{{post.posted_by.name}} <i class="fas fa-caret-right mx-1 text-md text-gray-500"></i> {{post.posted_on.name}}</p></div>

                    <p class="text-xs text-gray-600">{{post.created_at}}</p>
                </div>

                <div v-else class="flex-auto mx-4">
                    <p class="text-sm text-gray-600"><span class="font-bold text-blue-700">{{post.posted_by.name}}</span> shared a <span class="font-bold text-blue-700">Post </span></p>

                    <div><p class="text-xs text-gray-600">{{post.created_at}} <i class="fas fa-retweet ml-1"></i></p></div>
                </div>

                <div class="dropdown inline-block relative">
                    <button class="text-xl font-bold px-4 rounded items-center focus:outline-none">...</button>

                    <ul class="dropdown-menu absolute hidden text-gray-700 text-sm shadow-lg border border-gray-400">
                        <li><button @click="commitEditPost(post, $vnode.key)" class="w-24 py-2 px-4 block text-left font-semibold bg-white hover:bg-gray-300 focus:outline-none">Edit</button></li>
                        <li><button @click="dispatchDeletePost(post.id, $vnode.key)" class="w-24 py-2 px-4 block text-left font-semibold bg-white hover:bg-gray-300 focus:outline-none">Delete</button></li>
                    </ul>
                </div>
            </div>

            <div class="mt-4">
                <p>{{post.body}}</p>
            </div>
        </div>

        <div v-if="post.pictures.picture_count != 0" class="flex px-4">
            <div v-for="picture in post.pictures.data" class="w-full ml-1 mt-2">
                <img :src="'/storage/' + picture.path" class="w-full" alt="Post Picture">
            </div>
        </div>

        <div v-if="post.shared_post" class="flex flex-col border-t border-b border-gray-400 bg-gray-200 my-1">
            <div v-if="post.shared_post.pictures.picture_count != 0" class="flex">
                <div v-for="picture in post.shared_post.pictures.data">
                    <img :src="'/storage/' + picture.path" alt="Post Picture">
                </div>
            </div>

            <div v-else class="flex justify-between items-center pt-4 px-8">
                <img class="w-8 h-8 object-cover rounded-full" :src="'/storage/' + post.shared_post.posted_by.profile_image.path" alt="Profile Image">

                <div class="flex-auto mx-4">
                    <p class="text-sm font-bold text-blue-700">{{post.shared_post.posted_by.name}}</p>
                    <p class="text-xs text-gray-600">{{post.shared_post.created_at}}</p>
                </div>
            </div>

            <div class="py-4 px-8 text-sm font-medium">
                <p>{{post.shared_post.body}}</p>
            </div>
        </div>

        <div class="flex justify-between p-4 text-sm text-gray-700 font-medium">
            <div class="flex">
                <div class="flex items-center h-6 w-6 rounded-full bg-blue-500 text-white mr-1"><i class="fas fa-thumbs-up mx-auto text-xs"></i></div>

                {{post.likes.like_count}}
            </div>

            <div class="flex">
                <p class="mr-4">{{post.comments.data.length}} Comments</p>

                <p class="ml-4">{{post.shared_count}} Shares</p>
            </div>
        </div>

        <div class="flex justify-between items-center border-t border-gray-300 text-sm text-gray-700 py-2">
            <button @click="dispatchLikePost(post.id, $vnode.key)" :class="likeColor"><i class="fas fa-thumbs-up mr-1"></i> Like</button>

            <button @click="commentMode = ! commentMode" class="w-full font-medium hover:text-blue-600 focus:outline-none"><i class="fas fa-comments mr-1"></i> Comments</button>

            <button @click="shareMode = true" class="w-full font-medium hover:text-blue-600 focus:outline-none"><i class="fas fa-share mr-1"></i> Share</button>
        </div>

        <div v-if="shareMode" class="w-screen h-screen bg-black bg-opacity-25 absolute z-0 left-0 top-0 right-0 bottom-0"></div>

        <div v-if="shareMode" class="absolute inset-0 flex justify-center items-center">
            <ShareCard :post="post" :post_index="$vnode.key" />
        </div>

        <div v-if="commentMode">
            <CreateComment :post_id="post.id" :post_index="$vnode.key"></CreateComment>

            <div v-for="(comment, index) in post.comments.data">
                <CommentCard :comment="comment" :comment_index="index" :post="post" :post_index="$vnode.key" />
            </div>
        </div>
    </div>
</template>

<script>
    import CommentCard from "./CommentCard";
    import CreateComment from "../Comment/CreateComment";
    import ShareCard from "./ShareCard";

    export default {
        name: "PostCard",

        components: {ShareCard, CreateComment, CommentCard},

        props: ['post'],

        computed: {
            likeColor() {
                return this.post.likes.user_liked ? 'w-full text-blue-600 font-medium focus:outline-none' : 'w-full hover:text-blue-700 font-medium focus:outline-none'
            }
        },

        data() {
            return {
                commentBody: '',
                commentMode: false,
                shareMode: false,
            }
        },

        created() {
            EventBus.$on('changingShareMode', () => {
                this.shareMode = false
            })
        },

        methods: {
            commitEditPost(post, index) {
                this.$store.commit('splicePost', {post, index})

                EventBus.$emit('changingEditMode', post)
            },

            dispatchDeletePost(post_id, index) {
                this.$store.dispatch('deletePost', {post_id, index})
            },

            dispatchLikePost(post_id, index) {
                this.$store.dispatch('likeDislikePost', {post_id, index})
            }
        }
    }
</script>

<style scoped>
    .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
