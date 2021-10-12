import Vue from 'vue';
import Vuex from 'vuex';
import period from './components/states/period';
import start from './components/states/start';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    period,
    start,
  },
  plugins: [
    createPersistedState({
      key: 'task-manager',
      paths: [
        'period.selected_period',
        'start.is_started',
      ],
      storage: window.sessionStorage,
    }),
  ],
});

export default store;
  