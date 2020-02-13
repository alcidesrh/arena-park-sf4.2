import fetch from '../../../utils/fetch';
import {
    USER_LIST_ERROR,
    USER_LIST_LOADING,
    USER_LIST_RESET,
    USER_LIST_VIEW,
    USER_LIST_SUCCESS
} from './mutation-types';

const state = {
    loading: false,
    error: '',
    items: [],
    all: [],
    view: [],
    itemsPerPage: 20,
    totalItems: 0,
    page: 1
};

function error(error) {
    return {type: USER_LIST_ERROR, error};
}

function loading(loading) {
    return {type: USER_LIST_LOADING, loading};
}

function success(items) {
    return {type: USER_LIST_SUCCESS, items};
}

function view(items) {
    return {type: USER_LIST_VIEW, items};
}

function all(items) {
    return {type: 'all', items};
}

const getters = {
    error: state => state.error,
    items: state => state.items,
    loading: state => state.loading,
    view: state => state.view,
    itemsPerPage: state => state.itemsPerPage,
    totalItems: state => state.totalItems,
    all: state => state.all
};

const actions = {
    getItems({commit}, query = null) {
        commit(loading(true));
        let page = page = '/users?itemsPerPage=' + state.itemsPerPage + '&page=' + state.page;
        if (query)
            page = page + '&' + query;
        return fetch(page)
            .then(response => response.json())
            .then(data => {
                commit(loading(false));
                data['hydra:member'].forEach(element => {
                    element.selected = false;
                });
                commit(success(data['hydra:member']));
                commit(view(data['hydra:view']));
                let total = data['hydra:totalItems'];
                commit({type: 'setTotalItems', total});
            })
            .catch(e => {
                commit(loading(false));
                commit(error(e.message));
            });
    },
    setItemsPerPage({commit}, number) {
        commit({type: 'setItemsPerPage', number});
    },
    setPage({commit}, page) {
        commit({type: 'setPage', page});
    },
    setTotalItems({commit}, total) {
        commit({type: 'setTotalItems', total});
    },
    getUsersByIds({commit}, ids) {
        commit(loading(true));
        let page = page = '/users-by-ids';
        return fetch(page, {
        method: 'POST',
        headers: new Headers({'Content-Type': 'application/ld+json'}),
        body: JSON.stringify(ids),
      }
    )
            .then(response => response.json())
            .then(data => {
                commit(loading(false));
                commit(all(data));
            })
            .catch(e => {
                commit(loading(false));
                commit(error(e.message));
            });
    },
    sendEmail({commit}, param) {
        let page = page = '/send-email';
        return fetch(page, {
        method: 'POST',
        headers: new Headers({'Content-Type': 'application/ld+json'}),
        body: JSON.stringify(param),
      }
    )
            .then(response => response.json())
            .then(data => {
            })
            .catch(e => {
                commit(loading(false));
                commit(error(e.message));
            });
    },
};

const mutations = {
    [USER_LIST_ERROR](state, payload) {
        state.error = payload.error;
    },
    [USER_LIST_LOADING](state, payload) {
        state.loading = payload.loading;
    },
    [USER_LIST_VIEW](state, payload) {
        state.view = payload.items;
    },
    [USER_LIST_SUCCESS](state, payload) {
        state.items = payload.items;
    },
    [USER_LIST_RESET](state) {
        state.items = [];
    },
    setItemsPerPage(state, payload) {
        state.itemsPerPage = payload.number;
    },
    setTotalItems(state, payload) {
        state.totalItems = payload.total;
    },
    setPage(state, payload) {
        state.page = payload.page;
    },
    all(state, payload) {
        state.all = payload.items;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
