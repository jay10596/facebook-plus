<template>
    <div class="w-5/6 bg-white rounded mt-4 shadow">
        <div class="flex px-2 py-2 items-center border-b border-gray-400 justify-between">
            <p class="text-gray-700 font-semibold text-sm">Birthdays</p>

            <button @click="archiveMode = ! archiveMode" class="text-blue-600 font-medium text-sm"><i class="fas fa-eye-slash"></i> Archive</button>
        </div>

        <div v-if="! archiveMode" v-for="(birthdayUser, index) in birthdays.week" :key="index" class="flex p-2 justify-between items-center">
            <img class="w-8 h-8 object-cover rounded-full" :src="'/storage/' + birthdayUser.profile_image.path" alt="Profile Image">

            <div class="flex-auto mx-4">
                <p class="text-sm font-bold text-blue-700">{{birthdayUser.name}}</p>

                <div>
                    <p v-if="birthdayUser.birthday.when > 0" class="text-xs text-gray-600">Has birthday in {{birthdayUser.birthday.when}} Days</p>
                    <div v-else-if="birthdayUser.birthday.when == 0">
                        <p v-if="birthdayUser.id != authUser.id" class="text-xs text-gray-600">Has birthday Today</p>
                        <p v-else class="text-xs text-gray-700 font-semibold">It's your birthday today!</p>
                    </div>
                    <p v-else class="text-xs text-gray-600">Had birthday {{birthdayUser.birthday.when}} Days ago</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "BirthdayFeature",

        computed: {
            ...mapGetters({
                authUser: 'authUser',
                birthdays: 'birthdays',
            })
        },

        data() {
            return {
                archiveMode: false
            }
        },

        created() {
            this.$store.dispatch('fetchAllBirthdays');
        }
    }
</script>

<style scoped>

</style>
