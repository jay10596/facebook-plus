<template>
    <div>
        <img :class="avatarClass" :src="'/storage/' + avatarObject.path" :alt="avatarAlt" ref="userAvatar">
    </div>
</template>

<script>
    import Dropzone from 'dropzone';
    import { mapGetters } from 'vuex';

    export default {
        name: "UploadAvatar",

        props: ['newAvatar', 'avatarClass', 'avatarAlt', 'avatarWidth', 'avatarHeight', 'avatarType'],

        data() {
            return {
                dropzone: null
            }
        },

        mounted() {
            if(this.authUser.id == this.$route.params.userId) {
                this.dropzone = new Dropzone(this.$refs.userAvatar, this.settings);
            }
        },

        computed: {
            ...mapGetters({
                authUser: 'authUser',
            }),

            settings() {
                return {
                    paramName: 'avatar', //field name is image
                    url: '/api/upload-avatars',
                    acceptedFiles: 'image/*',
                    params: {
                        'width': this.avatarWidth,
                        'height': this.avatarHeight,
                        'type' : this.avatarType
                    },
                    headers: {
                        //'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,

                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    success: (e, res) => {
                        /*  One Way

                            this.cover_image = res will not work because we can not mutate the props.
                            The image will change in the profile but not in posts, comments and navbar.

                            this.uploadedImage = res.data
                         */
                        this.$store.dispatch('fetchAuthUser')
                        this.$store.dispatch('fetchUser', this.$route.params.userId)
                        this.$store.dispatch('fetchUserPosts', this.$route.params.userId)
                        this.$store.dispatch('fetchAllAvatars', this.$route.params.userId)
                    }
                };
            },

            //Tt is not required if we are dispatching events because as we are not changing uploadedImage, it will call this.newImage anyway. Just change it on the :scr at the top.
            avatarObject() {
                return null || this.newAvatar
            }
        }

    }
</script>

<style scoped>

</style>
