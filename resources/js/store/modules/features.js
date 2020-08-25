import Posts from './posts'

const state = {
    birthdays: '',
    birthdayErrors: '',
    avatars: ''
};

const getters = {
    birthdays: state => {
        return state.birthdays;
    },

    birthdayErrors: state => {
        return state.birthdayErrors;
    },

    avatars: state => {
        return state.avatars;
    },
};

const actions = {
    fetchAllBirthdays({commit, state}) {
        axios.post('/api/filter-birthdays')
            .then(res => commit('setBirthdays', res.data))
            .catch(err => commit('setBirthdayErrors', err))
    },

    wishBirthday({commit, state}, data) {
        axios.post('/api/wish-birthday', {body: data.body, friend_id: data.friend_id})
            .then(res => {
                commit('pushPost', res.data)
                commit('setPostBody', '')
            })
            .catch(err => commit('setBirthdayErrors', err))
    },

    fetchAllAvatars({commit, state}, user_id) {
        axios.post('/api/get-all-avatars', {user_id: user_id})
            .then(res => commit('setAvatars', res.data))
    },

    setPostBody(state, body) {
        Posts.state.body = body
    },

    pushPost(state, newPost) {
        Posts.state.posts.unshift(newPost.data)
    },
};

const mutations = {
    setBirthdays(state, birthdays) {
        state.birthdays = birthdays
    },

    setBirthdayErrors(state, err) {
        state.birthdayErrors = err
    },

    setAvatars(state, avatars) {
        state.avatars = avatars
    }
};

export default {
    state, getters, actions, mutations
}
