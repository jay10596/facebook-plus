<template>
    <div class="relative w-56 h-max rounded shadow-lg mr-6 mb-4 bg-white">
        <router-link :to="'/items/' + item.id">
            <img class="w-full" v-if="item.images.image_count > 0" :src="'/storage/' + item.images.data.slice(-1)[0].path" alt="Post Picture">

            <img v-else class="w-full" src="https://thumbs.dreamstime.com/b/no-image-available-icon-photo-camera-flat-vector-illustration-132483141.jpg" alt="Post Picture">
        </router-link>

        <div class="absolute w-max text-center">
            <p class="-my-8 ml-2 px-1 rounded text-white text-sm bg-gray-900">${{item.price}}</p>
        </div>

        <div class="px-4 py-2">
            <div class="font-medium h-6 text-base overflow-y-hidden">{{item.title}}</div>

            <p class="mt-4 h-16 text-gray-800 text-sm overflow-y-hidden">{{item.description}}</p>

            <div class="flex justify-between items-center mt-4">
                <p class="text-xs text-gray-600">{{item.created_at}}</p>

                <button @click="dispatchBookmarkItem(item.id, index)" :class="bookmarkColor" class="focus:outline-none"><i class="fas fa-bookmark"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ItemCard",

        props: ['item', 'index'],

        computed: {
            bookmarkColor() {
                return this.item.bookmarks.user_bookmarked ? 'text-md text-blue-600' : 'text-md text-gray-500'
            }
        },

        methods: {
            dispatchBookmarkItem(item_id, item_index) {
                this.$store.dispatch('bookmarkUnbookmarkItem', {item_id, item_index})
            }
        }
    }
</script>

<style scoped>

</style>
