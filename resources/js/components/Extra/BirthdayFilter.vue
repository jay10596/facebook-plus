<template>
    <div class="w-5/6 bg-white rounded my-4 shadow">
        <div class="flex justify-between items-center px-2 py-2  border-b border-gray-400">
            <p class="text-gray-800 font-semibold text-sm">{{title}}</p>

            <p class="text-gray-600 font-medium text-sm">{{current_date}}</p>
        </div>

        <div v-for="(birthdayUser, index) in birthdays" :key="birthdayUser.id" class="">
            <birthday-card :birthdayUser="birthdayUser" />
        </div>
    </div>
</template>

<script>
    import BirthdayCard from "./BirthdayCard";

    export default {
        name: "BirthdayFilter",

        components: {BirthdayCard},

        props: ['birthdays', 'title', 'current_date']
    }
</script>

<style scoped>

</style>
