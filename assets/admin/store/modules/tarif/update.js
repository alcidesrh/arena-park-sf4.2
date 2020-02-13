import SubmissionError from '../../../error/SubmissionError';
import fetch from '../../../utils/fetch';
import {
  TARIF_UPDATE_RESET,
  TARIF_UPDATE_UPDATE_ERROR,
  TARIF_UPDATE_UPDATE_LOADING,
  TARIF_UPDATE_UPDATE_SUCCESS,
  TARIF_UPDATE_RETRIEVE_ERROR,
  TARIF_UPDATE_RETRIEVE_LOADING,
  TARIF_UPDATE_RETRIEVE_SUCCESS,
  TARIF_UPDATE_UPDATE_VIOLATIONS
} from './mutation-types';

const state = {
  loading: false,
  retrieveError: '',
  retrieveLoading: false,
  retrieved: null,
  updated: null,
  updateError: '',
  updateLoading: false,
  violations: null,
  discounts: [],
};

function retrieveError(retrieveError) {
  return {type: TARIF_UPDATE_RETRIEVE_ERROR, retrieveError};
}

function retrieveLoading(retrieveLoading) {
  return {type: TARIF_UPDATE_RETRIEVE_LOADING, retrieveLoading};
}

function retrieveSuccess(retrieved) {
  return {type: TARIF_UPDATE_RETRIEVE_SUCCESS, retrieved};
}

function updateError(updateError) {
  return {type: TARIF_UPDATE_UPDATE_ERROR, updateError};
}

function updateLoading(updateLoading) {
  return {type: TARIF_UPDATE_UPDATE_LOADING, updateLoading};
}

function updateSuccess(updated) {
  return {type: TARIF_UPDATE_UPDATE_SUCCESS, updated};
}

function violations(violations) {
  return {type: TARIF_UPDATE_UPDATE_VIOLATIONS, violations};
}
function discounts(discounts) {
  return {type: 'discounts', discounts};
}

function reset() {
  return {type: TARIF_UPDATE_RESET};
}

const getters = {
  loading: state => state.loading,
  retrieveError: state => state.retrieveError,
  retrieveLoading: state => state.retrieveLoading,
  retrieved: state => state.retrieved,
  updated: state => state.updated,
  updateError: state => state.updateError,
  updateLoading: state => state.updateLoading,
  violations: state => state.violations,
  discounts: state => state.discounts
};

const actions = {
  delete({ commit }, item) {
    commit(retrieveLoading(true));

    return fetch(item['@id'], {method: 'DELETE'})
      .then(() => {
        commit(retrieveLoading(false));
      })
      .catch(e => {
        commit(retrieveLoading(false));
        commit(retrieveError(e.message));
      });
  },
  getDiscount({ commit }, page = '/discounts') {
      commit(retrieveLoading(true));

      return fetch(page)
        .then(response => response.json())
        .then(data => {
          commit(retrieveLoading(false));
          commit(discounts(data['hydra:member']));
        })
        .catch(e => {
          commit(retrieveLoading(false));
          commit(retrieveError(e.message));
        });
    },
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
  create({ commit }, values) {
    commit(retrieveLoading(true));

    return fetch('/discounts', {method: 'POST', body: JSON.stringify(values)})
      .then(response => {
        commit(retrieveLoading(false));

        return response.json();
      })
      .then(data => {
        return data
      })
      .catch(e => {
        commit(retrieveLoading(false));

        if (e instanceof SubmissionError) {
          commit(violations(e.errors));
          commit(error(e.errors._error));
          return;
        }

        commit(error(e.message));
      });
  },
  updateDiscount({ commit, state }, { item, values }) {
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
     discounts(state, payload) {
      state.discounts = payload.discounts;
    },
    [TARIF_UPDATE_RETRIEVE_ERROR] (state, payload) {
      state.retrieveError = payload.retrieveError;
    },
    [TARIF_UPDATE_RETRIEVE_LOADING] (state, payload) {
      state.retrieveLoading = payload.retrieveLoading;
    },
    [TARIF_UPDATE_RETRIEVE_SUCCESS] (state, payload) {
      state.retrieved = payload.retrieved;
    },
    [TARIF_UPDATE_UPDATE_LOADING] (state, payload) {
      state.updateLoading = payload.updateLoading;
    },
    [TARIF_UPDATE_UPDATE_ERROR] (state, payload) {
      state.updateError = payload.updateError;
    },
    [TARIF_UPDATE_UPDATE_SUCCESS] (state, payload) {
      state.updated = payload.updated;
      state.violations = null;
    },
    [TARIF_UPDATE_UPDATE_VIOLATIONS] (state, payload) {
      state.violations = payload.violations;
    },
    [TARIF_UPDATE_RESET] (state) {
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
