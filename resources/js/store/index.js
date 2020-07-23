import Vue from 'vue';
import Vuex from 'vuex';
import Auth from './modules/auth.js'
import Title from './modules/title.js'
import Request from './modules/request.js'
import Posts from './modules/posts.js'
import Comments from './modules/comments.js'
import Items from './modules/items.js'
import Categories from './modules/categories.js'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        Auth,
        Title,
        Request,
        Posts,
        Comments,
        Items,
        Categories
    }
});
