<template>
    <div class="form-signin">
        <form @submit.prevent="submit">
            <h1 class="h3 mb-3 fw-normal">Please register</h1>

            <input v-model="form.name" class="form-control" placeholder="Name" required>

            <input v-model="form.phone" type="text" class="form-control" placeholder="Phone" minlength="11" maxlength="11" required>

            <input v-model="form.email" type="email" class="form-control" placeholder="Email" required>

            <input v-model="form.password" type="password" class="form-control" placeholder="Password" minlength="5" required>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Submit</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios'

export default {
  name: "Register",

  data(){
      return {
          form: {
            name: '',
            phone: '',
            email: '',
            password: ''
          }
      }
  },

  methods:{

    async submit(){
            const response = await axios.post('http://127.0.0.1:8000/api/register', this.form).then(() => {
              this.$router.replace({
                  name: 'Login',
              })
          })
      }
  }

}
</script>