<template>
    <ItemDetailCard :item="item" />
</template>

<script>
    import ItemDetailCard from "../Extra/ItemDetailCard";
    import {mapGetters} from "vuex";

    export default {
        name: "ShowItem",

        components: {ItemDetailCard},

        mounted() {
            this.$store.dispatch('showItem', this.$route.params.itemId)
        },

        computed: {
            ...mapGetters({
                item: 'item',
                status: 'itemStatus'
            }),
        }
    }
</script>

<style scoped>

</style>
