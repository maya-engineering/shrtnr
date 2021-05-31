<template>
  <div class="form-signin">
    <form @submit.prevent="submit">
      <h1 class="h3 mb-3 fw-normal text-center">Shorten Your URL</h1>

      <input v-model="form.original_link" type="text" class="form-control" placeholder="Enter a long URL to make a ShortURL" required>

      <button class="w-100 btn btn-lg btn-success" type="submit">Make ShortURL!</button>
    </form>
    <div class="shortUrl" v-if="short_url">
      <span class=" text-dark text-center font-weight-bold">Your Short URL : </span> 
      <a href="#" @click="linkClick" target="_blank"> {{ short_url}} </a>
      <button class="mt-2 btn btn-md btn-secondary" @click="clearBtn">Clear</button>
    </div>
  </div>
</template>

<script>
import {onMounted, ref} from 'vue';
import {useStore} from "vuex";
import axios from 'axios'

export default {
  name: "Home",

  data(){
      return {
          form: {
            original_link: '',
          },
          short_url: '',
          original_url: '',
      }
  },

  methods:{

    async submit(){
        const response = await axios.post('http://127.0.0.1:8000/api/url-shortener-guest', this.form)
          this.short_url = response.data.shortUrl
          this.original_url = response.data.originalURL
      },

      linkClick (){
        window.open(``+this.original_url, '_blank')
      },

      clearBtn(){
        this.form.original_link = ''
        this.short_url = ''
        this.original_url = ''
      }
  }

}
</script>

<style scoped>
  .shortUrl{
    margin-top : 25px;
  }

  .shortUrl span{
    padding: 10px;
    font-weight: 600;
  }

  .shortUrl a{
    padding-left: 15%;
  }

  .shortUrl button{
    float: right;
  }
</style>