<template>
    <div v-if="item" class="flex flex-col items-center py-4">
        <div class="w-2/3 bg-white rounded mt-6 shadow">
            <div v-if="item.images.image_count != 0" class="flex">
                <div  v-for="image in item.images.data" :class="multipleImagesClass">
                    <img :src="'/storage/' + image.path" alt="Item Picture">
                </div>
            </div>

            <img v-else class="w-full" src="https://thumbs.dreamstime.com/b/no-image-available-icon-photo-camera-flat-vector-illustration-132483141.jpg" alt="Post Picture">

            <div v-if="! editMode" class="flex flex-col p-4">
                <div class="flex justify-between items-center">
                    <p class="text-2xl font-medium">{{item.title}}</p>

                    <button class="text-gray-700 text-sm font-medium"><i class="fas fa-share"></i>  Share</button>
                </div>

                <p v-if="! editMode" class="text-xl font-normal text-green-600">${{item.price}}</p>

                <button class="mt-2 h-8 bg-blue-700 text-white text-sm font-semibold shadow-2xl focus:outline-none">Message Seller</button>

                <button class="mt-2 h-8 bg-white border border-gray-500 text-gray-600 text-sm font-semibold shadow-2xl focus:outline-none">Make Offer</button>
            </div>

            <div v-else class="flex flex-col p-4">
                <div class="flex justify-between items-center">
                    <input v-model="item.title" class="w-9/12 text-2xl font-medium focus:outline-none">

                    <div>
                        <button @click="dispatchEditItem(item.id, item.title, item.description, item.price), editMode = false" class="px-2 py-1 text-sm bg-blue-700 rounded text-white font-medium">Save</button>
                        <button @click="dispatchCancelEdit(item.id, item.title, item.description, item.price), editMode = false" class="px-2 py-1 text-sm bg-white border border-gray-500 text-gray-600 rounded font-medium">Cancel</button>
                    </div>
                </div>

                <div class="flex text-xl font-normal text-green-600">
                    <p>$</p><input v-model="item.price" class="focus:outline-none">
                </div>

                <button class="mt-2 h-8 bg-blue-700 text-white text-sm font-semibold shadow-2xl focus:outline-none">Message Seller</button>

                <button class="mt-2 h-8 bg-white border border-gray-500 text-gray-600 text-sm font-semibold shadow-2xl focus:outline-none">Make Offer</button>
            </div>

            <div class="p-4">
                <div class="flex justify-between items-center">
                    <img class="w-8 h-8 object-cover rounded-full" :src="'/storage/' + item.posted_by.profile_image.path" alt="Profile Image">

                    <div class="flex-auto mx-4">
                        <p class="text-sm font-bold">{{item.posted_by.name}}</p>
                        <p class="text-xs text-gray-600">{{item.created_at}}</p>
                    </div>

                    <div class="dropdown inline-block relative">
                        <button class="text-xl font-bold px-4 rounded items-center focus:outline-none">...</button>

                        <ul class="dropdown-menu pt-1 absolute hidden text-gray-700 text-sm">
                            <li><button @click="editMode = true" class="w-24 py-2 px-4 block text-left rounded-t font-semibold bg-gray-400 hover:bg-gray-300 focus:outline-none">Edit</button></li>

                            <li><button @click="dispatchDeleteItem(item.id)" class="w-24 py-2 px-4 block text-left rounded-b font-semibold bg-gray-400 hover:bg-gray-300 focus:outline-none">Delete</button></li>
                        </ul>
                    </div>
                </div>

                <div v-if="! editMode" class="mt-4">
                    <p>{{item.description}}</p>
                </div>

                <div v-else class="mt-4">
                    <textarea v-model="item.description" class="w-full focus:outline-none"></textarea>
                </div>
            </div>

            <div class="flex justify-between p-4 text-sm">
                <p><i class="fas fa-bookmark text-gray-800 mr-1"></i>4 Bookmarks</p>

                <p>43 Replies</p>
            </div>

            <div class="flex p-4 text-sm border-t border-gray-300">
                <button class="w-1/2"><i class="far fa-bookmark text-gray-800 mr-1"></i>Bookmark</button>

                <button class="w-1/2"><i class="far fa-comment-alt"></i> Reply</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ItemDetailCard",

        props: ['item'],

        data() {
            return {
                editMode: false,
                replyMode: false,
            }
        },

        computed: {
            multipleImagesClass() {
                if(this.item.images.data.length > 1) {
                    return 'mx-2 mt-2' || ''
                }
            }
        },

        methods: {
            dispatchDeleteItem(item_id) {
                this.$store.dispatch('deleteItem', item_id)

                this.$router.push('/items')
            },

            dispatchCancelEdit() {
                this.$store.dispatch('showItem', this.$route.params.itemId)
            },

            dispatchEditItem(id, title, description, price) {
                this.$store.dispatch('editItem', {id, title, description, price})
            }
        }
    }
</script>

<style scoped>
    .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>
