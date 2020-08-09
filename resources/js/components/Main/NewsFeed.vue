<template>
    <div class="flex">
        <div class="flex flex-col w-8/12 items-center py-4">
            <CreatePost />

            <ShowPosts />
        </div>

        <ShowFeatures />
    </div>
</template>

<script>
    import CreatePost from "../Post/CreatePost";
    import ShowPosts from "../Post/ShowPosts";
    import ShowFeatures from "../Feature/ShowFeatures";

    export default {
        name: "NewsFeed",

        components: {ShowFeatures, CreatePost, ShowPosts}
    }
</script>

<style scoped>

</style>
