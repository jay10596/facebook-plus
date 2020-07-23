const state = {
    item: '',
    items: '',
    itemStatus: '',
    itemErrors: null,
};

const getters = {
    item: state => {
        return state.item;
    },

    items: state => {
        return state.items;
    },

    itemStatus: state => {
        return state.itemStatus;
    },

    itemErrors: state => {
        return state.itemErrors;
    }
};

const actions = {
    fetchAllItems({commit, state}) {
        commit('setItemStatus', 'loading')

        axios.get('api/items')
            .then(res => {
                commit('setItems', res.data.data)
                commit('setItemStatus', 'success')
            })
            .catch(err => commit('setItemErrors', err))
    },

    createItem({commit, state}, itemForm) {
        axios.post('/api/items', itemForm)
            .then(res => {
                commit('pushItem', res.data)
            })
            .catch(err => commit('setItemErrors', err))
    },

    showItem({commit, state}, itemId) {
        axios.get('/api/items/' + itemId)
            .then(res => {
                commit('setItem', res.data.data)
                commit('setItemStatus', 'success')
            })
            .catch(err => commit('setItemErrors', err))
    },

    editItem({commit, state}, itemForm) {
        axios.put('/api/items/' + itemForm.id, itemForm)
            .then(res => {
                commit('setItemStatus', 'success')
            })
            .catch(err => commit('setItemErrors', err))
    },

    deleteItem({commit, state}, itemId) {
        axios.delete('/api/items/' + itemId)
            .then(res => {
                commit('setItemStatus', 'success')
            })
            .catch(err => commit('setItemErrors', err))
    },

    bookmarkUnbookmarkItem({commit, state}, data) {
        axios.post('/api/items/' + data.item_id + '/bookmark-unbookmark')
            .then(res => {
                commit('pushBookmarks', {bookmarks: res.data, index: data.item_index})
            })
            .catch(err => commit('setItemErrors', err))
    },
};

const mutations = {
    setItems(state, items) {
        state.items = items
    },

    setItem(state, item) {
        state.item = item
    },

    pushItem(state, newItem) {
        state.items.unshift(newItem.data)
    },

    pushBookmarks(state, data) {
        state.items[data.index].bookmarks = data.bookmarks
    },

    setItemStatus(state, status) {
        state.itemStatus = status
    },

    setItemErrors(state, err) {
        state.itemErrors = err.response
    }
};

export default {
    state, getters, actions, mutations
}
