import SubmissionError from '../../../error/SubmissionError';
import fetch from '../../../utils/fetch';
import {
  AIRPORT_UPDATE_RESET,
  AIRPORT_UPDATE_UPDATE_ERROR,
  AIRPORT_UPDATE_UPDATE_LOADING,
  AIRPORT_UPDATE_UPDATE_SUCCESS,
  AIRPORT_UPDATE_RETRIEVE_ERROR,
  AIRPORT_UPDATE_RETRIEVE_LOADING,
  AIRPORT_UPDATE_RETRIEVE_SUCCESS,
  AIRPORT_UPDATE_UPDATE_VIOLATIONS
} from './mutation-types';

const state = {
  loading: false,
  retrieveError: '',
  retrieveLoading: false,
  retrieved: null,
  updated: null,
  updateError: '',
  updateLoading: false,
  violations: null
};

function retrieveError(retrieveError) {
  return {type: AIRPORT_UPDATE_RETRIEVE_ERROR, retrieveError};
}

function retrieveLoading(retrieveLoading) {
  return {type: AIRPORT_UPDATE_RETRIEVE_LOADING, retrieveLoading};
}

function retrieveSuccess(retrieved) {
  return {type: AIRPORT_UPDATE_RETRIEVE_SUCCESS, retrieved};
}

function updateError(updateError) {
  return {type: AIRPORT_UPDATE_UPDATE_ERROR, updateError};
}

function updateLoading(updateLoading) {
  return {type: AIRPORT_UPDATE_UPDATE_LOADING, updateLoading};
}

function updateSuccess(updated) {
  return {type: AIRPORT_UPDATE_UPDATE_SUCCESS, updated};
}

function violations(violations) {
  return {type: AIRPORT_UPDATE_UPDATE_VIOLATIONS, violations};
}

function reset() {
  return {type: AIRPORT_UPDATE_RESET};
}

const getters = {
  loading: state => state.loading,
  retrieveError: state => state.retrieveError,
  retrieveLoading: state => state.retrieveLoading,
  retrieved: state => state.retrieved,
  updated: state => state.updated,
  updateError: state => state.updateError,
  updateLoading: state => state.updateLoading,
  violations: state => state.violations
};

const actions = {
  retrieve({ commit }, id) {
    commit(retrieveLoading(true));

    return fetch(id)
      .then(response => response.json())
      .then(data => {
        commit(retrieveLoading(false));
        commit(retrieveSuccess(data));
      })
      .catch(e => {
        commit(retrieveLoading(false));
        commit(retrieveError(e.message));
      });
  },
  update({ commit, state }, { item, values }) {
    commit(updateError(null));
    commit(updateLoading(true));

    return fetch(item['@id'], {
        method: 'PUT',
        headers: new Headers({'Content-Type': 'application/ld+json'}),
        body: JSON.stringify(values),
      }
    )
      .then(response => response.json())
      .then(data => {
        commit(updateLoading(false));
        commit(updateSuccess(data));
      })
      .catch(e => {
        commit(updateLoading(false));

        if (e instanceof SubmissionError) {
          commit(violations(e.errors));
          commit(updateError(e.errors._error));
          return;
        }

        commit(updateError(e.message));
      });
  },
  reset({ commit }) {
    commit(reset());
  }
};

const mutations = {
    [AIRPORT_UPDATE_RETRIEVE_ERROR] (state, payload) {
      state.retrieveError = payload.retrieveError;
    },
    [AIRPORT_UPDATE_RETRIEVE_LOADING] (state, payload) {
      state.retrieveLoading = payload.retrieveLoading;
    },
    [AIRPORT_UPDATE_RETRIEVE_SUCCESS] (state, payload) {
      state.retrieved = payload.retrieved;
    },
    [AIRPORT_UPDATE_UPDATE_LOADING] (state, payload) {
      state.updateLoading = payload.updateLoading;
    },
    [AIRPORT_UPDATE_UPDATE_ERROR] (state, payload) {
      state.updateError = payload.updateError;
    },
    [AIRPORT_UPDATE_UPDATE_SUCCESS] (state, payload) {
      state.updated = payload.updated;
      state.violations = null;
    },
    [AIRPORT_UPDATE_UPDATE_VIOLATIONS] (state, payload) {
      state.violations = payload.violations;
    },
    [AIRPORT_UPDATE_RESET] (state) {
      state.loading = false;
      state.retrieveError = '';
      state.retrieveLoading = false;
      state.retrieved = null;
      state.updated = null;
      state.updateError = '';
      state.updateLoading = false;
      state.violations = null;
    }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
