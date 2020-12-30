const auth = {
    namespaced: true,
    state: () => ({ 
        token: null
    }),
    mutations: {
        setToken (state, token) {
            if(token) {
                state.token = token
            }
        }
    }
}

export default auth
