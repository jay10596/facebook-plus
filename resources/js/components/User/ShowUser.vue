<template>
    <div v-if="user">
        <div class="relative">
            <div class="w-100 h-64 overflow-hidden z-10">
                <UploadAvatar :newAvatar="user.cover_image" avatarClass="object-cover w-full" avatarAlt="Cover Image" avatarWidth="1500" avatarHeight="500" avatarType="cover"/>
            </div>

            <div class="absolute flex items-center bottom-0 left-0 -mb-8 z-20 mx-4">
                <UploadAvatar :newAvatar="user.profile_image" avatarClass="w-32 h-32 object-cover rounded-full shadow-lg border-4 border-gray-200" avatarAlt="Profile Image" avatarWidth="750" avatarHeight="750" avatarType="profile" />

                <p class="text-2xl text-gray-100 ml-3 shadow-2xl">{{user.name}}</p>
            </div>

            <div class="absolute flex items-center bottom-0 right-0 mb-4 z-20 mx-4">
                <button v-if="friendButton && friendButton !== 'Accept'" @click="sendFriendRequest" class="py-1 px-3 bg-gray-400 rounded">
                    <i class="fas fa-user-plus"></i> {{friendButton}}
                </button>

                <button v-if="friendButton && friendButton === 'Accept'" @click="acceptFriendRequest" class="py-1 px-3 bg-blue-500 mr-2 rounded">
                    <i class="fas fa-user-check"></i> Accept
                </button>

                <button v-if="friendButton && friendButton === 'Accept'" @click="deleteFriendRequest" class="py-1 px-3 bg-gray-400 mr-2 rounded">
                    <i class="fas fa-user-times"></i> Delete
                </button>
            </div>
        </div>

        <div class="flex flex-col items-center py-4">
            <p v-if="status.posts == 'loading' && posts.length < 1">Loading Posts...</p>

            <PostCard v-else v-for="(post, index) in posts" :key="index" :post="post"/>
        </div>
    </div>
</template>

<script>
    import PostCard from "../Extra/PostCard";
    import UploadAvatar from "../Extra/UploadAvatar";
    import { mapGetters } from 'vuex'

    export default {
        name: "ShowUser",

        components: {PostCard, UploadAvatar},

        computed: {
            ...mapGetters({
                user: 'user',
                posts: 'posts',
                friendButton: 'friendButton',
                userErrors: 'userErrors',
                status: 'status',
            })
        },

        created() {
            this.$store.dispatch('fetchUser', this.$route.params.userId)
            this.$store.dispatch('fetchUserPosts', this.$route.params.userId)
        },

        methods: {
            sendFriendRequest() {
                this.$store.dispatch('sendRequest', this.$route.params.userId)
            },

            acceptFriendRequest() {
                this.$store.dispatch('acceptRequest', this.$route.params.userId)
            },

            deleteFriendRequest() {
                this.$store.dispatch('deleteRequest', this.$route.params.userId)
            }
        },

        watch:{
            $route (to, from){
                this.$store.dispatch('fetchUser', this.$route.params.userId)
                this.$store.dispatch('fetchUserPosts', this.$route.params.userId)
            }
        }
    }
</script>

<style scoped>

</style>
