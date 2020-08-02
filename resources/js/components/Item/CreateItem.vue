<template>
    <div class="flex relative justify-center items-center">
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
                    <button ref="itemImage" class="w-96 h-8 pr-4 bg-blue-700 text-white text-sm font-semibold focus:outline-none">
                        <i class="fas fa-plus text-xs font-light"></i> Sell Item
                    </button>
                </div>
            </div>

            <div v-if="!sellMode" class="mx-4 my-4 text-xs">
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

            <div v-else class="w-full px-4 py-4">
                <div class="flex-col mb-6">
                    <label class="text-gray-600 font-semibold text-xs">Title</label>

                    <input v-model="itemForm.title" class="appearance-none border-b border-gray-400 w-full text-gray-800 text-sm focus:outline-none focus:bg-white focus:border-blue-500" placeholder="Add a title">
                </div>

                <div class="flex-col justify-start items-center mb-6">
                    <label class="text-gray-600 font-semibold text-xs">Description</label>

                    <input v-model="itemForm.description" class="appearance-none border-b border-gray-400 w-full text-gray-800 text-sm focus:outline-none focus:bg-white focus:border-blue-500" placeholder="Add a description of the item">
                </div>

                <div class="flex-col justify-start items-center mb-6">
                    <label class="text-gray-600 font-semibold text-xs">Price</label>

                    <input v-model="itemForm.price" class="appearance-none border-b border-gray-400 w-full text-gray-800 text-sm focus:outline-none focus:bg-white focus:border-blue-500" placeholder="$0.0">
                </div>

                <div class="flex-col justify-start items-center mb-6">
                    <label class="text-gray-600 font-semibold text-xs mr-4">Category</label>

                    <div class="inline-block relative w-64">
                        <select v-model="itemForm.category_id" class="block appearance-none w-full bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option>Please select one</option>
                            <option v-for="category in categories" :value="category.id">{{category.name}}</option>
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-caret-down"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="addItem(itemForm)" class="px-2 py-1 mr-2 bg-blue-700 text-white text-sm font-semibold shadow-md focus:outline-none" type="button">Upload</button>

                    <button @click="cancelItem" class="px-2 py-1 mr-2 bg-gray-200 text-gray-600 text-sm font-semibold shadow-md border border-gray-400 focus:outline-none" type="button">Cancel</button>
                </div>
            </div>

            <div class="dropzone-previews flex">
                <div id="dz-template" class="hidden">
                    <div class="dz-preview dz-file-preview mt-4">
                        <div class="dz-details mr-1">
                            <img data-dz-thumbnail class="w-32 h-32" alt="">

                            <button data-dz-remove class="mt-2 ml-6 text-sm focus:outline-none"> <i class="fas fa-minus-circle text-red-500"></i> Remove</button>
                        </div>

                        <div class="dz-progress">
                            <span class="dz-upload" data-dz-upload></span>
                        </div>
                    </div>
                </div>
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
                    category_id: 'Please select one' //This needs to be exactly the same as the image first option tag with without loop
                },
                sellMode: false,
                dropzone: null
            }
        },

        mounted() {
            this.dropzone = new Dropzone(this.$refs.itemImage, this.settings);
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
                    autoProcessQueue: false, //When the image is uploaded, it sends it right away which will give the error becasue we do not have the body in params.
                    previewsContainer: ".dropzone-previews",
                    previewTemplate: document.querySelector('#dz-template').innerHTML,
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
            this.$store.dispatch('fetchAllCategories')
        },

        methods: {
            addItem() {
                this.dropzone.processQueue()
                this.itemForm = ''
                this.createMode = false
            },

            cancelItem() {
                this.dropzone.removeAllFiles()
                this.itemForm = ''
                this.createMode = false
            },
        },

        watch: {
            'dropzone.files.length'(newValue, oldValue) {
                if(newValue > 0) {
                    this.sellMode = true
                } else {
                    this.sellMode = false
                }
            }
        }
    }
</script>

<style scoped>

</style>
