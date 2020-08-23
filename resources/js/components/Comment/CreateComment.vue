<template>
    <div class="relative flex border-t border-gray-400 p-4 py-2">
        <input v-model='body' @input="checkTags(body)" type="text" name="comment" placeholder="Add your comment..." class="w-full pl-4 h-8 bg-gray-200 rounded-lg focus:outline-none">

        <div v-if="tagMode" @click="tagMode = false" class="fixed right-0 left-0 top-0 bottom-0"></div>

        <div v-if="tagMode" class="absolute bg-white w-56 mt-8 top-0 text-xs shadow-2xl z-20 border border-gray-300">
            <div v-for="user in searchResult" :key='user.id'>
                <button @click="tagUser(user.name), tagMode = false" class="flex w-full items-center p-2 text-gray-800 font-semibold border-b border-gray-200 hover:bg-blue-700 hover:text-white">
                    <img class="w-8 h-8 object-cover" :src="'/storage/' + user.profile_image.path" alt="Profile Image">

                    <p class="mx-2">{{user.name}}</p>
                </button>
            </div>
        </div>

        <button v-if="body" @click="dispatchAddComment(body, post_id, post_index), body = ''"  class="bg-gray-200 ml-2 px-2 py-1 rounded-lg focus:outline-none">Post</button>

        <button ref="commentGif" class="dz-clickable mx-2 w-8 h-8 rounded-full text-xl bg-gray-200 focus:outline-none">
            <p class="dz-clickable"><i class="fas fa-camera"></i></p>
        </button>
    </div>
</template>

<script>
    import Dropzone from 'dropzone';
    import {mapGetters} from "vuex";

    export default {
        name: "CreateComment",

        props: ['post_id', 'post_index'],

        data() {
            return {
                body: '',
                dropzone: null,
                tagMode: false,
                hasTag: false
            }
        },

        mounted() {
            this.dropzone = new Dropzone(this.$refs.commentGif, this.settings);
        },

        computed: {
            ...mapGetters({
                searchResult: 'searchResult',
            }),

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
            },

            checkTags(body) {
                if(body.includes('@') && ! this.hasTag) { //Because we are allowing to use @ only once. Only dispatch result if @ doesn't exist at all.
                    let index = body.indexOf('@')
                    let searchTerm = body.substring(index + 1, index + 2)

                    this.tagMode = true
                    this.$store.dispatch('fetchSearchResult', searchTerm)
                }
            },

            tagUser(name) { //I can pass body from the top as well but then I will have to creat 2 different buttons for editMode true and false which why this is another way to make <template> code look simple
                if(this.editMode) {
                    this.post.body = this.post.body.replace('@', `@${name} `)
                } else {
                    this.body = this.body.replace('@', `@${name} `)
                }
                this.tagMode = false
                this.hasTag = true
            }
        }
    }
</script>

<style scoped>

</style>
