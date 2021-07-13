import Vue from 'vue';
import Vuex from 'vuex';
import period from './components/states/period';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    period,
  },
  plugins: [
    createPersistedState({
      key: 'task-manager',
      paths: [
        'period.selected_period',
      ],
      storage: window.sessionStorage,
    }),
  ],
});

export default store;
  