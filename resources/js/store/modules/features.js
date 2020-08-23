const state = {
    birthdays: '',
    birthdayErrors: '',
};

const getters = {
    birthdays: state => {
        return state.birthdays;
    },

    birthdayErrors: state => {
        return state.birthdayErrors;
    }
};

const actions = {
    fetchAllBirthdays({commit, state}) {
        axios.post('/api/filter-birthdays')
            .then(res => commit('setBirthdays', res.data))
            .catch(err => commit('setBirthdayErrors', err))
    },

    wishBirthday({commit, state}, data) {
        axios.post('/api/wish-birthday', {body: data.body, friend_id: data.friend_id})
            .then(res => console.log(res.data))
            .catch(err => commit('setBirthdayErrors', err))
    }
};

const mutations = {
    setBirthdays(state, birthdays) {
        state.birthdays = birthdays
    },

    setBirthdayErrors(state, err) {
        state.birthdayErrors = err
    }
};

export default {
    state, getters, actions, mutations
}
