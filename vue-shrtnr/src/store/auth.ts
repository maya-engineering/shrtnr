import axios from 'axios'

export default{
    namespaced: true,

    state: {
        token: null,
        user: null,
    },

    getters: {
        authenticated (state:any){
            return state.token && state.user
        },

        user(state:any){
            return state.user
        },

    },

    mutations: {
        SET_TOKEN (state:any, token:any){
            state.token = token
        },

        SET_USER (state:any, data:any){
            state.user = data
        },
    },

    actions: {
        async signIn( { dispatch }:any, credentials:any){
            
            const response = await axios.post('http://127.0.0.1:8000/api/login', credentials)

            if(response.data.message == 'Invalid Credential'){
                return 'Invalid Credential'
            }
            
            return dispatch('attempt', response.data.token)
            
        },

        async attempt ({ commit, state }:any, token:any){
            
            if(token){
                commit('SET_TOKEN', token)
            }

            if(!state.token){
                return
            }

            try{
                const response = await axios.get('http://127.0.0.1:8000/api/user')
                commit('SET_USER', response.data)
            }
            catch(e){
                commit('SET_TOKEN', null)
                commit('SET_USER', null)
            }

        },

        signOut({commit}:any){
            return axios.get('http://127.0.0.1:8000/api/logout').then(() => {
                commit('SET_TOKEN', null)
                commit('SET_USER', null)              
            })
        }

    }

}


