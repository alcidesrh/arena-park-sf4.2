import fetch from '../../../utils/fetch';
import {
  CONTACTEMAIL_DELETE_ERROR,
  CONTACTEMAIL_DELETE_LOADING,
  CONTACTEMAIL_DELETE_SUCCESS,
  CONTACTEMAIL_DELETE_RESET
} from './mutation-types';

const state = {
  loading: false,
  error: '',
  deleted: null
};

function error(error) {
  return {type: CONTACTEMAIL_DELETE_ERROR, error};
}

function loading(loading) {
  return {type: CONTACTEMAIL_DELETE_LOADING, loading};
}

function success(deleted) {
  return {type: CONTACTEMAIL_DELETE_SUCCESS, deleted};
}

function reset() {
  return {type: CONTACTEMAIL_DELETE_RESET};
}

const getters = {
  error: state => state.error,
  deleted: state => state.deleted,
  loading: state => state.loading,
};

const actions = {
  delete({ commit }, item) {
    commit(loading(true));

    return fetch(item['@id'], {method: 'DELETE'})
      .then(() => {
        commit(loading(false));
        commit(success(item));
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
    [CONTACTEMAIL_DELETE_ERROR] (state, payload) {
      state.error = payload.error;
    },
    [CONTACTEMAIL_DELETE_LOADING] (state, payload) {
      state.loading = payload.loading;
    },
    [CONTACTEMAIL_DELETE_SUCCESS] (state, payload) {
      state.deleted = payload.deleted;
    },
    [CONTACTEMAIL_DELETE_RESET] (state) {
      state.error = '';
      state.loading = false;
      state.deleted = null;
    }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
