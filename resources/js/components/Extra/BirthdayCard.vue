<template>
    <div v-if="birthdayUser.id != authUser.id" class="flex p-2 items-center">
        <img class="w-20 h-20 object-cover rounded-full" :src="'/storage/' + birthdayUser.profile_image.path" alt="Profile Image">

        <div class="w-full ml-4">
            <div class="flex justify-between">
                <p class="text-sm font-bold text-blue-700">{{birthdayUser.name}}</p>

                <p class="text-sm font-medium text-gray-600">{{birthdayUser.birthday.age}} years old</p>
            </div>

            <textarea v-if="createMode" v-model="body" rows="2" class="w-full mt-2 p-2 border border-gray-500 text-xs text-gray-700" placeholder="Wish your friend birthday here..."></textarea>
            <p v-else class="py-2 text-xs font-normal text-gray-600">The post has been successfully submitted on{{birthdayUser.name}}'s newsfeed.</p>

            <button v-if="createMode" @click="dispatchCreateBirthdayPost(body, birthdayUser.id)" class="text-xs font-semibold text-blue-700">Send</button>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "BirthdayCard",

        props: ['birthdayUser'],

        computed: {
            ...mapGetters({
                authUser: 'authUser',
            })
        },

        data() {
            return {
                body: '',
                createMode: true,
                alreadyWished: [],
            }
        },

        created() {
            this.alreadyWished = localStorage.getItem("alreadyWished").split(",")

            if (this.alreadyWished.includes(this.birthdayUser.id.toString())) {
                this.createMode = false
            }
        },

        methods: {
            dispatchCreateBirthdayPost(body, friend_id) {
                this.$store.dispatch('wishBirthday', {body, friend_id})
                this.createMode = false
                localStorage.setItem("alreadyWished", `${localStorage.getItem("alreadyWished")},${this.birthdayUser.id}`);
            }
        }
    }
</script>

<style scoped>

</style>
