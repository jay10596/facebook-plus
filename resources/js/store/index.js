import Vue from 'vue';
import Vuex from 'vuex';
import Auth from './modules/auth.js'
import Title from './modules/title.js'
import Request from './modules/request.js'
import Posts from './modules/posts.js'
import Comments from './modules/comments.js'
import Items from './modules/items.js'
import Categories from './modules/categories.js'
import User from './modules/user.js'
import Features from './modules/features.js'
import Notifications from './modules/notifications.js'
import Search from './modules/search.js'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        Auth,
        Title,
        Request,
        Posts,
        Comments,
        Items,
        Categories,
        User,
        Features,
        Notifications,
        Search
    }
});
