<template>
    <div class="flex border-t border-gray-400 p-4 py-2">
        <input v-model='body' type="text" name="comment" placeholder="Add your comment..." class="w-full pl-4 h-8 bg-gray-200 rounded-lg focus:outline-none">

        <button v-if="body" @click="dispatchAddComment(body, post_id, post_index), body = ''"  class="bg-gray-200 ml-2 px-2 py-1 rounded-lg focus:outline-none">Post</button>

        <button ref="commentGif" class="dz-clickable mx-2 w-8 h-8 rounded-full text-xl bg-gray-200 focus:outline-none">
            <p class="dz-clickable"><i class="fas fa-camera"></i></p>
        </button>
    </div>
</template>

<script>
    import Dropzone from 'dropzone';

    export default {
        name: "CreateComment",

        props: ['post_id', 'post_index'],

        data() {
            return {
                body: '',
                dropzone: null
            }
        },

        mounted() {
            this.dropzone = new Dropzone(this.$refs.commentGif, this.settings);
        },

        computed: {
            settings() {
                return {
                    paramName: 'gif', // field name is image
                    url: '/api/upload-gif',
                    acceptedFiles: 'image/*',
                    clickable: '.dz-clickable', // <i> will not work as it is not a button. To make sure all the inner elements of button are clickable.
                    autoProcessQueue: true, // True because we do not want to wait till post button. The comment should be added once the image is uploaded.
                    maxFiles: 1,
                    parallelUploads: 1,
                    uploadMultiple: false,
                    params: { //Cannot pass body here because settings() load when the component is mounted. Use sending.
                        'width': 150,
                        'height': 150,
                    },
                    headers: {
                        //'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content, (For api, when token is not needed)

                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    sending: (file, xhr, postForm) => {
                        postForm.append('body', this.body)
                        postForm.append('post_id', this.post_id)
                    },
                    success: (e, res) => {
                        this.dropzone.removeAllFiles()

                        this.$store.commit('pushComment', {post_index: this.post_index, comment: res.data})

                        this.body = ''
                    },
                    maxfilesexceeded: file => {
                        this.dropzone.removeAllFiles()

                        this.dropzone.addFile(file)
                    }
                }
            }
        },

        methods: {
            dispatchAddComment(body, post_id, post_index) {
                this.$store.dispatch('createComment', {body, post_id, post_index})
            }
        }
    }
</script>

<style scoped>

</style>
