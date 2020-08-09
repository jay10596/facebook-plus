<template>
    <div class="flex">
        <div class="flex flex-col w-8/12 items-center">
            <BirthdayFilter :birthdays="birthdays.today" title="Today's Birthdays" :current_date="current_date"  />
            <BirthdayFilter :birthdays="birthdays.week" title="This Week's Birthdays" />
            <BirthdayFilter :birthdays="birthdays.month" title="This Month's Birthdays" />
        </div>

        <ShowFeatures />
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import ShowFeatures from "./ShowFeatures";
    import BirthdayFilter from "../Extra/BirthdayFilter";

    export default {
        name: "ShowBirthdays",

        components: {BirthdayFilter, ShowFeatures},

        computed: {
            ...mapGetters({
                birthdays: 'birthdays',
            })
        },

        data() {
            return {
                current_date: new Date().toString().slice(4,15),
            }
        },

        created() {
            this.$store.dispatch('fetchAllBirthdays');
        }
    }
</script>

<style scoped>

</style>
