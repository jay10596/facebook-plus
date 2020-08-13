<template>
    <div class="flex h-screen items-center justify-center">
        <div class="w-full max-w-xs">
            <form @submit.prevent="dispatchLogin(loginForm)" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>

                    <input v-model="loginForm.email" class="mb-3 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username">

                    <p v-if="authErrors && authErrors.meta.email" class="text-red-500 text-xs italic">{{authErrors.meta.email[0]}}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>

                    <input v-model="loginForm.password" class="mb-3 shadow appearance-none rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">

                    <p v-if="authErrors && authErrors.meta.password" class="text-red-500 text-xs italic">{{authErrors.meta.password[0]}}</p>
                </div>

                <p v-if="authErrors && authErrors.meta.unauthorised" class="mb-4 text-red-500 text-xs italic">{{authErrors.meta.unauthorised}}</p>

                <div class="flex items-center justify-between mb-4">
                    <button @click="dispatchLogin(loginForm)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none">Log In</button>

                    <a @click="changeRegisterMode" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Click Here To Register!</a>
                </div>

                <div class="flex items-center justify-end text-xl">
                    <a href="api/login/github" ><i class="fab fa-github mx-2"></i></a>
                    <a href="api/login/google" ><i class="fab fa-google ml-2 text-green-600"></i></a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        name: "Login",

        props: ['title'],

        data() {
            return {
                loginForm: {
                    email: '',
                    password: ''
                }
            }
        },

        computed: {
            ...mapGetters({
                authErrors: 'authErrors'
            })
        },

        methods: {
            dispatchLogin(loginForm) {
                this.$store.dispatch('loginUser', loginForm)
            },

            changeRegisterMode() {
                EventBus.$emit('changingRegisterMode')
            },

        }
    }
</script>

<style scoped>

</style>
