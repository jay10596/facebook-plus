<template>
    <div>
        <CreateItem />

        <p v-if="status == 'loading'">Loading Items...</p>

        <div v-else class="flex flex-wrap w-full justify-start items-center mx-12 my-4">
            <div v-for="(item, index) in items" :key="index">
                <ItemCard v-if="$route.path == '/items'" :item="item" :index="index" />

                <ItemCard v-if="$route.path == '/category1items' && item.category_id == 1" :item="item" />
                <ItemCard v-if="$route.path == '/category2items' && item.category_id == 2" :item="item" />
                <ItemCard v-if="$route.path == '/category3items' && item.category_id == 3" :item="item" />
                <ItemCard v-if="$route.path == '/category4items' && item.category_id == 4" :item="item" />
                <ItemCard v-if="$route.path == '/category5items' && item.category_id == 5" :item="item" />
            </div>
        </div>
    </div>
</template>

<script>
    import CreateItem from "./CreateItem";
    import ItemCard from "../Extra/ItemCard";
    import {mapGetters} from "vuex";

    export default {
        name: "ShowItems",

        components: {CreateItem, ItemCard},

        computed: {
            ...mapGetters({
                items: 'items',
                status: 'itemStatus'
            })
        },

        created() {
            this.$store.dispatch('fetchAllItems');
        }
    }
</script>

<style scoped>

</style>
