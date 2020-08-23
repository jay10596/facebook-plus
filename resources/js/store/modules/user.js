import router from "../../router";

const state = {
    users: '',
};

const getters = {
    users: state => {
        return state.users;
    }
};

const actions = {
    fetchAllUsers({commit, state}) {
        axios.get('/api/users')
            .then(res => commit('setUsers', res.data.data))
    },

    updateUser({commit, state}, user) {
        axios.put('/api/users/' + user.id, {
            name: user.name,
            email: user.email,
            city: user.city,
            gender: user.gender,
            birthday: user.birthday.day + '-' + user.birthday.month + '-' + user.birthday.year,
            interest: user.interest,
            about: user.about
        })
            .then(res => router.push('/'))
            .catch(err => console.log(err))
    },
};

const mutations = {
    setUsers(state, data) {
        state.users = data
    },
};

export default {
    state, getters, actions, mutations
}
