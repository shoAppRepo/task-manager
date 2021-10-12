const state = {
  is_started :{
    itemIndex: null, 
    taskIndex: null,
    task_id: null, 
    man_hour_id: null
  }
};

const mutations = {
  setIsStarted(state, is_started){
    state.is_started = is_started;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
};