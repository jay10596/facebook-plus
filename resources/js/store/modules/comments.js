import Posts from './posts'

const state = {
    //Unlike posts, we will pass the body from the vue file as a parameter (Just to do it differently)
    commentErrors: null
};

const getters = {
    commentErrors: state => {
        return state.commentErrors;
    }
};

const actions = {
    createComment({commit, state}, data) {
        axios.post('/api/posts/' + data.post_id + '/comments', {body: data.body})
            .then(res => commit('pushComments', {comments: res.data, post_index: data.post_index}))
            .catch(err => commit('setCommentErrors', err))
    },

    updateComment({commit, state}, data) {
        axios.put('/api/posts/' + data.post_id + '/comments/' + data.comment_id, {body: data.comment_body})
            .then(res => commit('pushComments', res.data))
            .catch(err => commit('setPostErrors', err))
    },

    deleteComment({commit, state}, data) {
        axios.delete('/api/posts/' + data.post_id + '/comments/' + data.comment_id)
            .then(res => commit('spliceComment', data))
            .catch(err => commit('setCommentErrors', err))
    },

    favouriteUnfavouriteComment({commit, state}, data) {
        axios.post('/api/posts/' + data.post_id + '/comments/' + data.comment_id + '/favourite-unfavourite', {type: data.type})
            .then(res => commit('pushFavourites', {favourites: res.data, post_index: data.post_index, comment_index: data.comment_index}))
            .catch(err => commit('setCommentErrors', err))
    },
};

const mutations = {
    setCommentErrors(state, err) {
        state.commentErrors = err.response
    },

    pushComments(state, data) {
        Posts.state.posts[data.post_index].comments = data.comments
    },

    // This is for the GifController. Just a different way. We are pushing the newly created gif comment.
    pushComment(state, data) {
        Posts.state.posts[data.post_index].comments.data.unshift(data.comment)
    },

    spliceComment(state, data) {
        Posts.state.posts[data.post_index].comments.data.splice(data.comment_index, 1)
    },

    /*
        Here we have to change user_favourited and favourited_type change manually as they are in CommentResource and not in the FavouriteCollection unlike Likes and Bookmarks.
        If user_favourited was in FavouriteCollection, it would've been updated by default using ID as the id field would be user_id in FavouriteResource.
        But it is only applicable in ManyToMany. As we are using HasMany, the $this->id in collection would refer to the id of favourite table's id which is useless.
    */
    pushFavourites(state, data) {
        //We need to replace favourites Only for counts.
        Posts.state.posts[data.post_index].comments.data[data.comment_index].favourites = data.favourites
        Posts.state.posts[data.post_index].comments.data[data.comment_index].user_favourited = ! Posts.state.posts[data.post_index].comments.data[data.comment_index].user_favourited
        Posts.state.posts[data.post_index].comments.data[data.comment_index].favourited_type = data.favourites.data[0].type
    }
};

export default {
    state, getters, actions, mutations
}
