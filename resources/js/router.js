import Vue from 'vue';
import VueRouter from 'vue-router';

import User from './store/helpers/user'
import NewsFeed from "./components/Main/NewsFeed";
import ShowUser from "./components/User/ShowUser";
import ShowItems from "./components/Item/ShowItems";
import ShowItem from "./components/Item/ShowItem";

let homeTitle = 'Sign Up';

if(User.loggedIn()) {
    homeTitle = 'NewsFeed'
}

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        { path: '/', component: NewsFeed, meta:{title: homeTitle} },

        { path: '/users/:userId', component: ShowUser, meta:{title: 'Profile'} },

        { path: '/items', component: ShowItems, meta:{title: 'Marketplace'} },
        { path: '/items/:itemId', component: ShowItem, meta:{title: 'Marketplace'} },


        { path: '/category1items', component: ShowItems, meta:{title: 'Categories'} },
        { path: '/category2items', component: ShowItems, meta:{title: 'Categories'} },
        { path: '/category3items', component: ShowItems, meta:{title: 'Categories'} },
        { path: '/category4items', component: ShowItems, meta:{title: 'Categories'} },
        { path: '/category5items', component: ShowItems, meta:{title: 'Categories'} },
    ],
    mode: 'history',
    hash: false
});
