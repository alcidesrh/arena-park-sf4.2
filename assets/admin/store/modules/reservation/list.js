import fetch from '../../../utils/fetch';
import {
    RESERVATION_LIST_ERROR,
    RESERVATION_LIST_LOADING,
    RESERVATION_LIST_RESET,
    RESERVATION_LIST_VIEW,
    RESERVATION_LIST_SUCCESS
} from './mutation-types';

const state = {
    loading: false,
    error: '',
    items: [],
    view: [],
    itemsPerPage: 20,
    totalItems: 0,
    page: 1
};

function error(error) {
    return {type: RESERVATION_LIST_ERROR, error};
}

function loading(loading) {
    return {type: RESERVATION_LIST_LOADING, loading};
}

function success(items) {
    return {type: RESERVATION_LIST_SUCCESS, items};
}

function view(items) {
    return {type: RESERVATION_LIST_VIEW, items};
}

const getters = {
    error: state => state.error,
    items: state => state.items,
    loading: state => state.loading,
    view: state => state.view,
    itemsPerPage: state => state.itemsPerPage,
    totalItems: state => state.totalItems
};

const actions = {
    getItems({commit}, query=null) {
        commit(loading(true));

        let page = page = '/reservations?itemsPerPage=' + state.itemsPerPage + '&page=' + state.page;
        if(query)
            page = page+'&'+query;
        return fetch(page)
            .then(response => response.json())
            .then(data => {
                commit(loading(false));
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
    }


};

const mutations = {
    [RESERVATION_LIST_ERROR](state, payload) {
        state.error = payload.error;
    },
    [RESERVATION_LIST_LOADING](state, payload) {
        state.loading = payload.loading;
    },
    [RESERVATION_LIST_VIEW](state, payload) {
        state.view = payload.items;
    },
    [RESERVATION_LIST_SUCCESS](state, payload) {
        state.items = payload.items;
    },
    [RESERVATION_LIST_RESET](state) {
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
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
