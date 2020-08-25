<template>
    <div v-if="user">
        <div class="bg-white">
            <div class="relative flex justify-center mx-32">
                <div class="w-100 h-64 overflow-hidden z-10 rounded-lg">
                    <UploadAvatar :newAvatar="user.cover_image" avatarClass="object-cover w-full" avatarAlt="Cover Image" avatarWidth="1500" avatarHeight="500" avatarType="cover"/>
                </div>

                <div class="absolute bottom-0 -mb-3 z-20">
                    <UploadAvatar :newAvatar="user.profile_image" avatarClass="w-32 h-32 object-cover rounded-full shadow-lg border-2 border-gray-200" avatarAlt="Profile Image" avatarWidth="750" avatarHeight="750" avatarType="profile" />
                </div>

                <div class="absolute flex items-center bottom-0 right-0 mb-4 z-20 mx-4">
                    <button v-if="friendButton && friendButton !== 'Accept'" @click="sendFriendRequest" class="py-1 px-3 bg-gray-400 text-sm text-gray-900 font-semibold rounded">
                        <i class="fas fa-user-plus"></i> {{friendButton}}
                    </button>

                    <button v-if="friendButton && friendButton === 'Accept'" @click="acceptFriendRequest" class="py-1 px-3 bg-blue-500 text-sm text-gray-900 font-semibold mr-2 rounded">
                        <i class="fas fa-user-check"></i> Accept
                    </button>

                    <button v-if="friendButton && friendButton === 'Accept'" @click="deleteFriendRequest" class="py-1 px-3 bg-gray-400 text-sm text-gray-900 font-semibold mr-2 rounded">
                        <i class="fas fa-user-times"></i> Delete
                    </button>
                </div>
            </div>

            <div class="mx-36 py-4 border-b-2 border-gray-400">
                <p class="mb-4 text-2xl text-gray-800 font-bold text-center">{{user.name}}</p>

                <p class="text-sm text-gray-600 font-semibold text-center">{{user.about}}</p>
            </div>

            <div class="mx-36 flex justify-between items-center">
                <div class="flex items-center h-full">
                    <button class="text-sm text-blue-600 font-bold px-2 py-3 border-b-2 border-blue-600">Timeline</button>
                    <button class="text-sm text-gray-700 font-bold px-2 py-3 border-b-2 border-white hover:border-blue-600">About</button>
                    <button class="text-sm text-gray-700 font-bold px-2 py-3 border-b-2 border-white hover:border-blue-600">Albums</button>
                    <button class="text-sm text-gray-700 font-bold px-2 py-3 border-b-2 border-white hover:border-blue-600">Friends</button>
                    <button class="text-sm text-gray-700 font-bold px-2 py-3 border-b-2 border-white hover:border-blue-600">More <i class="fas fa-caret-down"></i></button>
                </div>

                <div class="flex">
                    <button v-if="user.id == authUser.id" class="text-sm text-gray-700 font-bold my-2 mx-1 py-1 px-4 bg-gray-200 rounded-lg"><i class="fas fa-edit mr-2"></i>Edit Profile</button>
                    <button v-else class="text-sm text-gray-800 font-bold my-2 mx-1 py-1 px-4 bg-gray-200 rounded-lg shadow"><i class="fas fa-user-friends mr-2"></i>Friends <i class="fas fa-caret-down"></i></button>
                    <button class="text-sm text-gray-800 font-bold my-2 mx-1 py-1 px-4 bg-gray-200 rounded-lg shadow"><i class="fab fa-facebook-messenger"></i></button>
                    <button class="text-sm text-gray-800 font-bold my-2 ml-1 py-1 px-4 bg-gray-200 rounded-lg shadow"><i class="fas fa-ellipsis-h"></i></button>
                </div>
            </div>
        </div>

        <div class="flex mx-36">
            <div class="w-5/12 flex flex-col items-center my-4 mr-2">
                <div class="w-full rounded bg-white p-4 my-4 shadow">
                    <p class="text-md font-bold text-gray-900">About</p>

                    <p class="my-2 text-sm font-semibold text-gray-600"><i class="fas fa-map-marker-alt mr-2"></i>Lives in <span class="text-gray-800 font-bold">{{user.city}}</span></p>
                    <p class="my-2 text-sm font-semibold text-gray-600"><i class="fas fa-birthday-cake mr-2"></i>Birthday on <span class="text-gray-800 font-bold">{{user.birthday.day}}/{{user.birthday.month}}/{{user.birthday.year}}</span></p>
                    <p class="my-2 text-sm font-semibold text-gray-600"><i class="fas fa-heart mr-2"></i>Interested in <span class="text-gray-800 font-bold">{{user.interest}}</span></p>
                    <p v-if="user.gender == 'male'" class="my-2 text-sm font-semibold text-gray-600"><i class="fas fa-mars mr-2"></i>Male</p>
                    <p v-else class="my-2 text-sm font-semibold text-gray-600"><i class="fas fa-venus mr-2"></i>Female</p>

                    <div class="flex justify-center my-2">
                        <button class="w-full p-1 text-sm font-bold text-gray-800 bg-gray-200 text-center shadow">See More About {{user.name}}</button>
                    </div>
                </div>

                <div class="w-full rounded bg-white p-4 shadow">
                    <div class="flex justify-between">
                        <p class="text-md font-bold text-gray-900">Photos</p>
                        <button @click="seeAllMode = ! seeAllMode" class="text-md font-medium text-blue-600">See All</button>
                    </div>

                    <div class="flex flex-wrap justify-between">
                        <img v-if="! seeAllMode" v-for="avatar in avatars.slice(0, 4)" :key="avatar.id" class="w-35 h-35 my-1 object-cover" :src="'/storage/' + avatar.path" alt="Profile Image">
                        <img v-if="seeAllMode" v-for="avatar in avatars" :key="avatar.id" class="w-35 h-35 my-1 object-cover" :src="'/storage/' + avatar.path" alt="Profile Image">
                    </div>

                    <div class="flex justify-center my-2">
                        <button class="w-full p-1 text-sm font-bold text-gray-800 bg-gray-200 text-center shadow">See More About {{user.name}}</button>
                    </div>
                </div>
            </div>

            <div class="w-7/12 flex flex-col items-center py-4 ml-2">
                <CreatePost :type="'profile'" :friend_id="user.id" class="w-full mt-4" />

                <p v-if="status.posts == 'loading' && posts.length < 1">Loading Posts...</p>

                <PostCard class="w-full" v-else v-for="(post, index) in posts" :key="index" :post="post"/>
            </div>
        </div>
    </div>
</template>

<script>
    import PostCard from "../Extra/PostCard";
    import UploadAvatar from "../Extra/UploadAvatar";
    import { mapGetters } from 'vuex'
    import CreatePost from "../Post/CreatePost";

    export default {
        name: "ShowUser",

        components: {CreatePost, PostCard, UploadAvatar},

        computed: {
            ...mapGetters({
                user: 'user',
                authUser: 'authUser',
                avatars: 'avatars',
                posts: 'posts',
                friendButton: 'friendButton',
                userErrors: 'userErrors',
                status: 'status',
            })
        },

        data() {
            return {
                seeAllMode: false
            }
        },

        created() {
            this.$store.dispatch('fetchUser', this.$route.params.userId)
            this.$store.dispatch('fetchUserPosts', this.$route.params.userId)
            this.$store.dispatch('fetchAllAvatars', this.$route.params.userId)
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
