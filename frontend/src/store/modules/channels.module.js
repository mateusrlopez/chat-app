const state = {
  channelsList: []
}

const getters = {
  getChannelsList: state => state.channelsList
}

const actions = {
}

const mutations = {
  setChannelsList (state, channels) {
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
