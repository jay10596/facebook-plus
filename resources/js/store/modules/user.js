import router from "../../router";

const state = {

};

const getters = {

};

const actions = {
    updateUser({commit, state}, user) {
        axios.put('/api/users/' + user.id, {
            name: user.name,
            email: user.email,
            city: user.city,
            gender: user.gender,
            birthday: user.birthday.day + '/' + user.birthday.month + '/' + user.birthday.year,
            interest: user.interest,
            about: user.about
        })
            .then(res => router.push('/'))
            .catch(err => commit('setUserErrors', err))
    },
};

const mutations = {

};

export default {
    state, getters, actions, mutations
}
