<template>
    <div class="flex justify-center items-center">
        <div class="flex-col w-full h-max mx-12 my-4 bg-white shadow">
            <div class="flex justify-between items-center m-4">
                <div class="flex">
                    <input type="text" class="w-56 h-8 pl-4 text-sm bg-white border border-r-0 border-gray-300 shadow-sm focus:outline-none" placeholder="Enter Product Name">

                    <div class="relative flex items-center">
                        <div class="absolute ml-4 text-sm text-gray-400"><i class="fas fa-map-marker-alt"></i></div>
                        <input type="text" class="w-56 h-8 pl-8 text-sm bg-white border border-gray-300 shadow-sm focus:outline-none" placeholder="Search Location">
                    </div>

                    <button class="w-8 h-8 bg-gray-300 text-gray-600 shadow-sm focus:outline-none"><i class="fas fa-search"></i></button>
                </div>

                <div>
                    <button @click="createMode = ! createMode" ref="commentGif" class="dz-clickable w-96 h-8 pr-4 bg-blue-700 text-white text-sm font-semibold focus:outline-none">
                        <i class="fas fa-plus text-xs font-light"></i> Sell Item
                    </button>
                </div>
            </div>

            <div v-if="createMode">
                <form class="w-full max-w-sm my-4">
                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">Title</label>
                        </div>

                        <div class="md:w-2/3">
                            <input v-model="itemForm.title" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" placeholder="Enter the title">
                        </div>
                    </div>

                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">Description</label>
                        </div>

                        <div class="md:w-2/3">
                            <input v-model="itemForm.description" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" placeholder="Write description">
                        </div>
                    </div>

                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">Price</label>
                        </div>

                        <div class="md:w-2/3">
                            <input v-model="itemForm.price" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" placeholder="$0.0">
                        </div>
                    </div>

                    <div class="md:flex md:items-center mb-6">
                        <div class="md:w-1/3">
                            <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-username">Category</label>
                        </div>

                        <div class="inline-block relative w-64">
                            <select v-model="itemForm.category_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option v-for="category in categories" :value="category.id">{{category.name}}</option>
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-caret-down"></i>
                            </div>
                        </div>
                    </div>

                    <div class="md:flex md:items-center">
                        <div class="md:w-1/3"></div>

                        <div class="md:w-2/3">
                            <button @click="dispatchAddItem(itemForm), itemForm = '', createMode = false" class="shadow bg-blue-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">Sell it!</button>
                        </div>
                    </div>
                </form>
            </div>

            <div v-else class="mx-4 my-4 text-xs">
                <div class="inline-block relative mr-4">
                    <select class="block appearance-none w-max bg-gray-200 font-bold text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none">
                        <option>Location</option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fas fa-caret-down"></i>
                    </div>
                </div>

                <div class="inline-block relative mr-4">
                    <select class="block appearance-none w-max bg-gray-200 font-bold text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none">
                        <option>Price</option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fas fa-caret-down"></i>
                    </div>
                </div>

                <button class="mr-4 w-max bg-gray-200 font-bold text-gray-700 border border-gray-400 hover:border-gray-500 px-4 py-2 rounded shadow leading-tight focus:outline-none"><i class="fas fa-bolt mr-3"></i>Free</button>

                <button class="font-bold text-blue-800">Reset</button>
            </div>
        </div>
    </div>
</template>

<script>
    import Dropzone from "dropzone";
    import {mapGetters} from "vuex";

    export default {
        name: "CreateItem",

        data() {
            return {
                itemForm: {
                    title: '',
                    description: '',
                    price: '',
                    category_id: '',
                },
                createMode: false,
                dropzone: null
            }
        },

        mounted() {
            this.dropzone = new Dropzone(this.$refs.commentGif, this.settings);
        },

        computed: {
            ...mapGetters({
                categories: 'categories'
            }),

            settings() {
                return {
                    paramName: 'image', //field name is image
                    url: '/api/upload-images',
                    acceptedFiles: 'image/*',
                    clickable: '.dz-clickable', //<i> will not work as it is not a button. To make sure all the inner elements of button are clickable.
                    autoProcessQueue: false, //When the image is uploaded, it sends it right away which will give the error becasue we do not have the body in params.
                    //previewsContainer: '.dropzone-previews',
                    //previewTemplate: document.querySelector('#dz-template').innerHTML,
                    maxFiles: 5,
                    parallelUploads: 5,
                    uploadMultiple: true,
                    params: { //Cannot pass body here because settings() load when the component is mounted. Use sending.
                        'width': 750,
                        'height': 750,
                    },
                    headers: {
                        //'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content, (For api, when token is not needed)

                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    sending: (file, xhr, itemForm) => {
                        itemForm.append('title', this.itemForm.title)
                        itemForm.append('description', this.itemForm.description)
                        itemForm.append('price', this.itemForm.price)
                        itemForm.append('category_id', this.itemForm.category_id)
                    },
                    success: (e, res) => {
                        this.dropzone.removeAllFiles()

                        //this.$store.commit('setPostBody', '')

                        //this.$store.commit('pushPost', res) For multiple images post, it will commit the response multiple times.
                        this.$store.dispatch('fetchAllItems')
                    },
                    maxfilesexceeded: file => {
                        this.dropzone.removeAllFiles()

                        this.dropzone.addFile(file)
                    }
                }
            },
        },

        created() {
            this.$store.dispatch('fetchAllCategories');
        },

        methods: {
            dispatchAddItem() {
                this.dropzone.processQueue()
            },
        }
    }
</script>

<style scoped>

</style>
