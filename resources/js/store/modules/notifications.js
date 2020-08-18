const state = {
    notificationMode: false, //It's taken here rather than in the Vue to avoid the use of EventBus.
    allNotifications: {},
    readNotifications: {},
    unreadNotifications: {},
    unreadNotificationCount: 0,
    notificationErrors: null
};

const getters = {
    notificationMode: state => {
        return state.notificationMode;
    },

    allNotifications: state => {
        return state.allNotifications;
    },

    readNotifications: state => {
        return state.readNotifications;
    },

    unreadNotifications: state => {
        return state.unreadNotifications;
    },

    unreadNotificationCount: state => {
        return state.unreadNotificationCount.length;
    },

    notificationErrors: state => {
        return state.notificationErrors;
    }
};

const actions = {
    fetchAllNotifications({commit, state}) {
        axios.post('/api/notifications')
            .then(res => commit('setNotifications', res.data))
            .catch(err => commit('setNotificationErrors', err))
    },

    markAsRead({commit, state}, notification) {
        axios.post('/api/mark-as-read', {id: notification.id})
            .then(res => commit('setMarkAsRead', notification))
            .catch(err => commit('setBirthdayErrors', err))
    },

    hideFriendButtons({commit, state}, data) {
        axios.post('/api/hide-friend-buttons', data)
            .then(res => commit('setMarkAsRead', notification))
            .catch(err => commit('setBirthdayErrors', err))
    }
};

const mutations = {
    setNotificationMode(state, newMode) {
        state.notificationMode = newMode
    },

    setNotifications(state, notifications) {
        state.allNotifications = notifications.all
        state.readNotifications = notifications.read
        state.unreadNotifications = notifications.unread
    },

    setMarkAsRead(state, notification) {
        state.notificationMode = false
        state.unreadNotifications.splice(notification, 1)
        state.readNotifications.push(notification)
        state.unreadNotificationCount --
    },

    setNotificationErrors(state, err) {
        state.notificationErrors = err
    },
};

export default {
    state, getters, actions, mutations
}
