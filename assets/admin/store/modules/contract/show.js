import fetch from '../../../utils/fetch';
import {
  CONTRACT_SHOW_ERROR,
  CONTRACT_SHOW_LOADING,
  CONTRACT_SHOW_RETRIEVED_SUCCESS,
  CONTRACT_SHOW_RESET
} from './mutation-types';

const state = {
  loading: false,
  error: '',
  retrieved: null
};

function error(error) {
  return {type: CONTRACT_SHOW_ERROR, error};
}

function loading(loading) {
  return {type: CONTRACT_SHOW_LOADING, loading};
}

function retrieved(retrieved) {
  return {type: CONTRACT_SHOW_RETRIEVED_SUCCESS, retrieved};
}

function reset() {
  return {type: CONTRACT_SHOW_RESET};
}

const getters = {
  error: state => state.error,
  loading: state => state.loading,
  item: state => state.retrieved
};

const actions = {
  retrieve({ commit }, id) {
    commit(loading(true));

    return fetch(id)
      .then(response => response.json())
      .then(data => {
        commit(loading(false));
        commit(retrieved(data));
      })
      .catch(e => {
        commit(loading(false));
        commit(error(e.message));
      });
  },
  reset({ commit }) {
    commit(reset());
  }
};

const mutations = {
    [CONTRACT_SHOW_ERROR] (state, payload) {
      state.error = payload.error;
    },
    [CONTRACT_SHOW_LOADING] (state, payload) {
      state.loading = payload.loading;
    },
    [CONTRACT_SHOW_RETRIEVED_SUCCESS] (state, payload) {
      state.retrieved = payload.retrieved;
    },
    [CONTRACT_SHOW_RESET] (state) {
      state.retrieved = null;
    }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
