<template>
    <div>
        <div v-if="! loggedIn">
            <div v-if="! registerMode"> <Login /> </div>

            <div v-else> <Register /> </div>
        </div>

        <div v-else> <Home /> </div>
    </div>
</template>

<script>
    import Login from "./Auth/Login";
    import Register from "./Auth/Register";
    import Home from "./Main/Home";

    export default {
        name: "App",

        components: {Login, Register, Home},

        data() {
            return {
                loggedIn: User.loggedIn(),
                registerMode: false
            }
        },

        created() {
            this.$store.dispatch('getPageTitle', this.$route.meta.title)

            EventBus.$on('changingRegisterMode', () => {
                this.registerMode = ! this.registerMode
            })

            if (this.$attrs.email) {
                this.$store.dispatch('loginUser', {email: this.$attrs.email, password: 'password'})
            }
        },

        watch: {
            $route(to, from) {
                this.$store.dispatch('getPageTitle', to.meta.title)
            }
        }
    }
</script>

<style>

</style>
