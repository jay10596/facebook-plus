const state = {
    categories: '',
    categoryErrors: null,
};

const getters = {
    categories: state => {
        return state.categories;
    },

    categoryErrors: state => {
        return state.categoryErrors;
    },
};

const actions = {
    fetchAllCategories({commit, state}) {
        axios.get('api/categories')
            .then(res => {
                commit('setCategories', res.data.data)
            })
            .catch(err => commit('setCategoryErrors', err))
    },
};

const mutations = {
    setCategories(state, categories) {
        state.categories = categories
    },

    setCategoryErrors(state, err) {
        state.categoryErrors = err.response
    },
};

export default {
    state, getters, actions, mutations
}
