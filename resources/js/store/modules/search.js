const state = {
    searchResult: '',
    searchErrors: null
};

const getters = {
    searchResult: state => {
        return state.searchResult;
    },

    searchErrors: state => {
        return state.searchErrors;
    }
};

const actions = {
    fetchSearchResult({commit, state}, searchTerm) {
        axios.post('/api/search', {searchTerm: searchTerm})
            .then(res => commit('setSearchResult', res.data.data))
            .catch(err => commit('setSearchErrors', err))
    }
};

const mutations = {
    setSearchResult(state, data) {
        state.searchResult = data
    },

    setSearchErrors(state, err) {
        state.searchErrors = err
    },
};

export default {
    state, getters, actions, mutations
}
