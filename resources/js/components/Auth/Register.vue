<template>
    <div class="flex h-full items-center justify-center">
        <div class="w-full max-w-xs">
            <form @submit.prevent="dispatchRegister(registerForm)" class="bg-white shadow-md rounded px-8 pt-6 pb-8 my-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>

                    <input v-model="registerForm.name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username">

                    <p v-if="authErrors && authErrors.meta.name" class="text-red-500 text-xs italic">{{authErrors.meta.name[0]}}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>

                    <input v-model="registerForm.email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username">

                    <p v-if="authErrors && authErrors.meta.email" class="text-red-500 text-xs italic">{{authErrors.meta.email[0]}}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">City</label>

                    <input v-model="registerForm.city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username">

                    <p v-if="authErrors && authErrors.meta.city" class="text-red-500 text-xs italic">{{authErrors.meta.email[0]}}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Gender</label>

                    <div class="w-3/6 mr-4">
                        <div class="flex items-center">
                            <input v-model='registerForm.gender' type="radio" id="male" name="gender" value="male">
                            <label for="male" class="ml-1">Male</label>
                        </div>

                        <div class="flex items-center">
                            <input v-model='registerForm.gender' type="radio" id="female" name="gender" value="female">
                            <label for="female" class="ml-1">Female</label>
                        </div>
                    </div>

                    <p v-if="authErrors && authErrors.meta.email" class="text-red-500 text-xs italic">{{authErrors.meta.email[0]}}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Birthday</label>

                    <div class="flex">
                        <div class="flex-col justify-start items-center mr-4">
                            <div class="inline-block relative">
                                <select v-model="day" class="block w-12 h-6 px-2 appearance-none bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option v-for="i in 31">{{i}}</option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex-col justify-start items-center mr-4">
                            <div class="inline-block relative">
                                <select v-model="month" class="block w-16 h-6 px-2 appearance-none bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option v-for="(month, index) in months" :value="index + 1">{{month}}</option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex-col justify-start items-center mr-4">
                            <div class="inline-block relative">
                                <select v-model="year" class="block w-16 h-6 px-2 appearance-none bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option v-for="i in 2020" v-if="i > 1995">{{i}}</option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-if="authErrors && authErrors.meta.email" class="text-red-500 text-xs italic">{{authErrors.meta.email[0]}}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Interest</label>

                    <div class="w-3/6 flex mr-4">
                        <div class="flex items-center mr-4">
                            <input v-model='interested_in' type="checkbox" value="male" class="form-checkbox h-4 w-4">
                            <span class="ml-1">Male</span>
                        </div>

                        <div class="flex items-center">
                            <input v-model='interested_in' type="checkbox" value="female" class="form-checkbox h-4 w-4">
                            <span class="ml-1">Female</span>
                        </div>
                    </div>

                    <p v-if="authErrors && authErrors.meta.email" class="text-red-500 text-xs italic">{{authErrors.meta.email[0]}}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">About</label>

                    <textarea v-model="registerForm.about" rows="3" class="w-full px-2 appearance-none border border-gray-400 text-gray-800 text-sm shadow focus:outline-none focus:bg-white focus:border-blue-500" placeholder="Add a title"></textarea>

                    <p v-if="authErrors && authErrors.meta.email" class="text-red-500 text-xs italic">{{authErrors.meta.email[0]}}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>

                    <input v-model="registerForm.password" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">

                    <p v-if="authErrors && authErrors.meta.password" class="text-red-500 text-xs italic">{{authErrors.meta.password[0]}}</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>

                    <input v-model="registerForm.confirm_password" class="shadow appearance-none rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">

                    <p v-if="authErrors && authErrors.meta.confirm_password" class="text-red-500 text-xs italic">{{authErrors.meta.confirm_password[0]}}</p>
                </div>

                <div class="flex items-center justify-between">
                    <a @click="changeRegisterMode" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#"> < Back To Log In!</a>

                    <button @click="dispatchRegister" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Register</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "Register",

        data() {
            return {
                registerForm: {
                    name: '',
                    email: '',
                    city: '',
                    gender: '',
                    birthday: '',
                    interest: '',
                    about: '',
                    password: '',
                    confirm_password: ''
                },
                interested_in: [],
                months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                day: 1,
                month: 1,
                year: 1996,
            }
        },

        computed: {
            ...mapGetters({
                authErrors: 'authErrors'
            })
        },

        methods: {
            changeRegisterMode() {
                EventBus.$emit('changingRegisterMode')
            },

            dispatchRegister() {
                this.editFields()
                this.$store.dispatch('registerUser', this.registerForm)
            },

            editFields() {
                if (this.interested_in.length > 1) {
                    this.registerForm.interest = 'both'
                } else {
                    this.registerForm.interest = this.interested_in[0]
                }

                this.registerForm.birthday = this.year + '-' + this.month + '-' + this.day
            }
        }
    }
</script>

<style>

</style>
