<template >
<div class="form-signin">
    <form @submit.prevent="submit">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
    <input v-model="form.email" type="email" class="form-control" placeholder="Email" required>
    <input v-model="form.password" type="password" class="form-control" placeholder="Password" required>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <p v-if="isValid" style="color : red">Invalid Credential</p>
  </form>
</div>
</template>

<script>
import {mapActions} from 'vuex'

export default {
  name: "Login",
  
  data(){
      return {
          form: {
              email: '',
              password: '',
          },
          isValid: false,
      }
  },

  methods:{
      ...mapActions({
          signIn: 'auth/signIn'
      }),

      submit(){
          this.signIn(this.form).then((msg) => {
              if(msg == 'Invalid Credential'){
                this.isValid = true
              }else{
                    this.$router.replace({
                        name: 'Dashboard',
                    })
              }
          })
      }
  }
}
</script>