import fetch from '../../../utils/fetch';
import {
  CONTRACT_LIST_ERROR,
  CONTRACT_LIST_LOADING,
  CONTRACT_LIST_RESET,
  CONTRACT_LIST_VIEW,
  CONTRACT_LIST_SUCCESS
} from './mutation-types';

const state = {
  loading: false,
  error: '',
  items: [],
  view: []
};

function error(error) {
  return {type: CONTRACT_LIST_ERROR, error};
}

function loading(loading) {
  return {type: CONTRACT_LIST_LOADING, loading};
}

function success(items) {
  return {type: CONTRACT_LIST_SUCCESS, items};
}

function view(items) {
  return { type: CONTRACT_LIST_VIEW, items};
}

const getters = {
  error: state => state.error,
  items: state => state.items,
  loading: state => state.loading,
  view: state => state.view
};

const actions = {
    getItems({ commit }, page = '/contracts') {
      commit(loading(true));

      fetch(page)
        .then(response => response.json())
        .then(data => {
          commit(loading(false));
          commit(success(data['hydra:member']));
          commit(view(data['hydra:view']));
        })
        .catch(e => {
          commit(loading(false));
          commit(error(e.message));
        });
    }
};

const mutations = {
    [CONTRACT_LIST_ERROR] (state, payload) {
      state.error = payload.error;
    },
    [CONTRACT_LIST_LOADING] (state, payload) {
      state.loading = payload.loading;
    },
    [CONTRACT_LIST_VIEW] (state, payload) {
      state.view = payload.items;
    },
    [CONTRACT_LIST_SUCCESS] (state, payload) {
      state.items = payload.items;
    },
    [CONTRACT_LIST_RESET] (state) {
      state.items = [];
    }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
