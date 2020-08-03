<template>
    <div>
        <div class="w-full bg-white border-b-2 border-gray-400 shadow-sm">
            <p class="p-6 text-xl font-semibold text-gray-800">{{authUser.name}} <i class="fas fa-caret-right mx-2 text-gray-500"></i> Edit Profile</p>
        </div>

        <div class="p-4">
            <div class="flex text-center justify-center items-center">
                <UploadAvatar :newAvatar="authUser.profile_image" avatarClass="w-32 h-32 object-cover rounded-full shadow-lg border-4 border-gray-200" avatarAlt="Profile Image" avatarWidth="750" avatarHeight="750" avatarType="profile" />
            </div>

            <div class="w-full px-4 py-4 text-gray-600 font-bold text-sm">
                <div class="flex items-center py-6 border-b border-gray-400">
                    <label class="w-1/6 text-right mr-4">Name:</label>

                    <input v-model="authUser.name" class="w-3/6 h-8 px-2 appearance-none border border-gray-400 text-gray-800 text-sm shadow focus:outline-none focus:bg-white focus:border-blue-500" placeholder="Add a title">
                </div>

                <div class="flex items-center py-6 border-b border-gray-400">
                    <label class="w-1/6 text-right mr-4">Email Address:</label>

                    <input v-model="authUser.email" class="w-3/6 h-8 px-2 appearance-none border border-gray-400 text-gray-800 text-sm shadow focus:outline-none focus:bg-white focus:border-blue-500" placeholder="Add a title">
                </div>

                <div class="flex items-center py-6 border-b border-gray-400">
                    <label class="w-1/6 text-right mr-4">Current City:</label>

                    <input v-model="authUser.city" class="w-3/6 h-8 px-2 appearance-none border border-gray-400 text-gray-800 text-sm shadow focus:outline-none focus:bg-white focus:border-blue-500" placeholder="Add a title">
                </div>

                <div class="flex py-6 border-b border-gray-400">
                    <label class="w-1/6 text-right mr-4">I Am:</label>

                    <div class="w-3/6 mr-4">
                        <div class="flex items-center">
                            <input v-model='authUser.gender' type="radio" id="male" name="gender" value="male">
                            <label for="male" class="ml-1">Male</label>
                        </div>

                        <div class="flex items-center">
                            <input v-model='authUser.gender' type="radio" id="female" name="gender" value="female">
                            <label for="female" class="ml-1">Female</label>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" class="form-checkbox h-4 w-4" checked>
                        <label class="w-max ml-1 text-left">Show my sex in my profile</label>
                    </div>
                </div>

                <div class="flex py-6 border-b border-gray-400">
                    <label class="w-1/6 text-right mr-4">Birthday:</label>

                    <div class="w-3/6 mr-4">
                        <div class="flex">
                            <div class="flex-col justify-start items-center mr-4">
                                <div class="inline-block relative">
                                    <select v-model="authUser.name" class="block w-16 h-6 appearance-none bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 shadow leading-tight focus:outline-none focus:shadow-outline">
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>

                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-col justify-start items-center mr-4">
                                <div class="inline-block relative">
                                    <select v-model="authUser.name" class="block w-12 h-6 appearance-none bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 shadow leading-tight focus:outline-none focus:shadow-outline">
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>

                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-col justify-start items-center mr-4">
                                <div class="inline-block relative">
                                    <select v-model="authUser.name" class="block w-16 h-6 appearance-none bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 shadow leading-tight focus:outline-none focus:shadow-outline">
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>

                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-caret-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="inline-block relative">
                        <select class="block w-max h-6 pl-2 pr-6 appearance-none bg-white text-gray-800 text-sm border border-gray-400 hover:border-gray-500 shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option>Show my birthday to my friends only</option>
                            <option>Female</option>
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-caret-down"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center py-6 border-b border-gray-400">
                    <label class="w-1/6 text-right mr-4">Interested In:</label>

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
                </div>

                <div class="flex py-6 border-b border-gray-400">
                    <label class="w-1/6 text-right mr-4">About Me:</label>

                    <textarea v-model="authUser.about" rows="5" class="w-3/6 px-2 appearance-none border border-gray-400 text-gray-800 text-sm shadow focus:outline-none focus:bg-white focus:border-blue-500" placeholder="Add a title"></textarea>
                </div>

                <div class="flex justify-end py-6">
                    <button class="px-2 py-1 mr-2 bg-blue-700 text-white text-sm font-semibold shadow-md focus:outline-none" type="button">Save</button>

                    <button class="px-2 py-1 mr-2 bg-gray-200 text-gray-600 text-sm font-semibold shadow-md border border-gray-400 focus:outline-none" type="button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import UploadAvatar from "../Extra/UploadAvatar";

    export default {
        name: "EditUser",

        components: {UploadAvatar},

        computed: {
            ...mapGetters({
                authUser: 'authUser',
            }),
        },

        created() {
            if (this.authUser.interest != 'both') {
                this.interested_in.push(this.authUser.interest)
            } else {
                this.interested_in.push('male', 'female')
            }
        },

        data() {
            return {
                interested_in: []
            }
        }
    }
</script>

<style scoped>

</style>
