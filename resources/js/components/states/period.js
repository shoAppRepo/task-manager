const state = {
  selected_period :null,
};

const mutations = {
  setSelectedPeriod(state, id){
    state.selected_period = id;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
};